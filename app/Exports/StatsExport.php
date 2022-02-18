<?php

namespace App\Exports;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Year;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class StatsExport implements FromQuery, ShouldAutoSize, WithMapping, WithHeadings, WithStyles
{
    use Exportable;

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
                        'year' => $year,
                        'data_type' => $attribute->data_type
                    ];
                }
            } else {
                $result[] = [
                    'attribute_id' => $attribute->id,
                    'attribute' => $attribute,
                    'year_id' => null,
                    'year' => null,
                    'data_type' => $attribute->data_type
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

        $sheet->getStyle('2')->getAlignment()->setWrapText(true);
        $sheet->getStyle('3')->getFont()->setSize(10);

        $sheet->getRowDimension(1)->setRowHeight(5);
        $sheet->getRowDimension(2)->setRowHeight(50);

//        for ($rd = 3; $rd < 10000; $rd++) {
//            $sheet->getRowDimension($rd)->setRowHeight(20);
//        }

        $sheet->getDefaultRowDimension()->setRowHeight(20);

        $lastColumn = $sheet->getHighestColumn(1);

        $sheet->getStyle("A1:{$lastColumn}3")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('11ABEEB0');

        $sheet->getStyle("A1:{$lastColumn}2")
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM);

        $sheet->getStyle("A3:{$lastColumn}1000")
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A1:{$lastColumn}1000")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(50);

        foreach ($sheet->getColumnIterator('E') as $key => $column) {
            $sheet->getColumnDimension($key)->setWidth(20);
        }

        $sheet->setTitle('Товары и аттрибуты');
    }

    public function headings(): array
    {
        $headings = [
            [
                'product.id',
                'category.title',
                'brand.title',
                'product.title',
            ],
            [
                'ID товара',
                'Тип техники',
                'Бренд',
                'Наименование товара',
            ],
            [
                "Оставьте пустым \r\nдля добавления нового",
                'Строка',
                'Строка',
                'Строка',
            ]
        ];

        foreach ($this->attributesColumns as $item) {
            if ($item['year_id']) {
                $headings[0][] = "attribute.{$item['attribute_id']}.year.{$item['year_id']}";
                $headings[1][] = "{$item['attribute']->title} ({$item['year']->value} г.)";
            } else {
                $headings[0][] = "attribute.{$item['attribute_id']}";
                $headings[1][] = "{$item['attribute']->title}";
            }

            if ($item['data_type']) {
                $headings[2][] = __('data-type.description.' . $item['data_type']);
            }
        }

        return $headings;
    }

    public function query()
    {
        return Product::with(['brand', 'category', 'values']);
    }

    public function map($row) : array
    {
        $result = [
            $row->id,
            $row->category->title,
            $row->brand->title,
            $row->title,
        ];

        /** @var Collection $keyed */
        $keyed = $row->values->keyBy(function ($item) {
            return $item['attribute_id'] . '.' . $item['year_id'];
        });

        foreach ($this->attributesColumns as $column) {
            $attribute_id = $column['attribute_id'];
            $year_id = $column['year_id'];

//            $result[] = $row->values->where('attribute_id', $attribute_id)->where('year_id', $year_id)->first()->value ?? null;
            $result[] = $keyed->get($attribute_id . '.' . $year_id)->value ?? null;
        }

        return $result;
    }

    public function registerEvents(): array
    {
        return [
            // For some reason it is not working at all
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }

}
