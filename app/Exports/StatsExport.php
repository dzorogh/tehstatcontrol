<?php

namespace App\Exports;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Year;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class StatsExport implements FromCollection, WithStyles, WithHeadings
{
    private array $attributesColumns;

    public function __construct()
    {
        $this->attributesColumns = $this->getAttributesByYear();
    }

    private function getAttributesByYear(): array
    {
        $attributes = Attribute::query()
            ->orderBy('order')
            ->get();

        $years = Year::query()
            ->orderBy('value')
            ->get();

        $result = [];

        foreach ($attributes as $attribute) {

            if ($attribute->by_year) {
                foreach ($years as $year) {
                    $result[] = [
                        'attribute_id' => $attribute->id,
                        'attribute' => $attribute,
                        'year_id' => $year->id,
                        'year' => $year
                    ];
                }
            } else {
                $result[] = [
                    'attribute_id' => $attribute->id,
                    'attribute' => $attribute,
                    'year_id' => null,
                    'year' => null,
                ];
            }
        }

        return $result;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setItalic(true);
        $sheet->getStyle('1')->getFont()->setSize(6);

        $sheet->getStyle('2')->getAlignment()->setWrapText(true);
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setSize(10);

        $sheet->getRowDimension(1)->setRowHeight(5);
        $sheet->getRowDimension(2)->setRowHeight(50);

        for ($rd = 3; $rd < 10000; $rd++) {
            $sheet->getRowDimension($rd)->setRowHeight(20);
        }

        $sheet->getDefaultRowDimension()->setRowHeight(20);

        $lastColumn = $sheet->getHighestColumn(1);

        $sheet->getStyle("A1:{$lastColumn}2")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('11ABEEB0');

        $sheet->getStyle("A1:{$lastColumn}2")
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM);

        $sheet->getStyle("A3:{$lastColumn}1000")
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A1:{$lastColumn}1000")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(50);

        foreach ($sheet->getColumnIterator('D') as $key => $column) {
            $sheet->getColumnDimension($key)->setWidth(20);
        }

        $sheet->setTitle('Товары и аттрибуты');
    }

    public function headings(): array
    {
        $firstRow = [
            'category.title',
            'brand.title',
            'product.title',
        ];

        $secondRow = [
            'Тип техники',
            'Бренд',
            'Модель техники',
        ];

        foreach ($this->attributesColumns as $item) {

            if ($item['year_id']) {
                $firstRow[] = "attribute.{$item['attribute_id']}.year.{$item['year_id']}";
                $secondRow[] = "{$item['attribute']->title} ({$item['year']->value} г.)";
            } else {
                $firstRow[] = "attribute.{$item['attribute_id']}";
                $secondRow[] = "{$item['attribute']->title}";
            }
        }

        return [
            $firstRow,
            $secondRow
        ];
    }

    public function collection()
    {
        $products = Product::with(['brand', 'category', 'values'])->get();

        $result = $products->map(function (Product $item) {

            $row = [
                $item->category->title,
                $item->brand->title,
                $item->title,
            ];

            foreach ($this->attributesColumns as $column) {
                $attribute_id = $column['attribute_id'];
                $year_id = $column['year_id'];

                $row[] = $item->values->where('attribute_id', $attribute_id)->where('year_id', $year_id)->first()->value ?? null;
            }

            return $row;
        });

        return $result;
    }

    public function registerEvents(): array
    {
        return [
            // For some reason it is not working at all
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}
