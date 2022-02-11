<?php

namespace App\Imports;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Year;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;


/**
 * Within queue, you can't store params in object using dynamic object attribute.
 * It will be better to check existed attributes and years in header, but for now it hard to find good method for it.
 */
class StatsImport implements WithEvents, OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use RemembersRowNumber, RegistersEventListeners;

    public function onRow(Row $row)
    {
        HeadingRowFormatter::default('none');

        $rowIndex = $row->getIndex();
        $row = $row->toArray();

//        Log::info('Reading row', ['row' => $row, 'rowIndex' => $rowIndex]);

        if ($rowIndex > 2) {
            $productTitle = '';
            $brandTitle = '';
            $categoryTitle = '';
            $attributes = [];

            foreach ($row as $columnIndex => $columnValue) {
                if ($columnIndex === 'product.title') {
                    $productTitle = $columnValue;
                    continue;
                }

                if ($columnIndex === 'brand.title') {
                    $brandTitle = $columnValue;
                    continue;
                }

                if ($columnIndex === 'category.title') {
                    $categoryTitle = $columnValue;
                    continue;
                }

                $columnIndexSegments = explode('.', $columnIndex);

                if (isset($columnIndexSegments[0]) && $columnIndexSegments[0] === 'attribute' && isset($columnIndexSegments[1]) && $columnValue) {
                    $attribute = [];

                    $attribute['attribute_id'] = $columnIndexSegments[1];

                    if (isset($columnIndexSegments[3])) {
                        $attribute['year_id'] = $columnIndexSegments[3];
                    } else {
                        $attribute['year_id'] = null;
                    }

                    $attribute['value'] = $columnValue;

                    $attributes[] = $attribute;
                }
            }

//            Log::info('Read columns at row ' . $rowIndex, [
//                'productTitle' => $productTitle,
//                'brandTitle' => $brandTitle,
//                'categoryTitle' => $categoryTitle,
//                'attributes' => $attributes,
//            ]);

            if ($productTitle && $brandTitle && $categoryTitle) {

                $categoryId = Category::query()->firstOrCreate([
                    'title' => $categoryTitle
                ])->id;

                $brandId = Brand::query()->firstOrCreate([
                    'title' => $brandTitle
                ])->id;

//                Log::info('Brand and category', [
//                    'brandId' => $brandId,
//                    'categoryId' => $categoryId,
//                    'categoryTitle' => $categoryTitle,
//                    'brandTitle' => $brandTitle
//                ]);

                $product = Product::create([
                    'title' => $productTitle,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId
                ]);


//                Log::info('Product', [
//                    'product' => $product
//                ]);

                foreach ($attributes as $attribute) {
                    $attributeId = $attribute['attribute_id'];
                    $yearId = $attribute['year_id'];

                    AttributeValue::query()
                        ->create([
                            'year_id' => $yearId,
                            'attribute_id' => $attributeId,
                            'product_id' => $product->id,
                            'value' => $attribute['value']
                        ]);
                }
            }
        }
    }

    public static function beforeImport(BeforeImport $event)
    {
        Brand::query()->delete();
        Product::query()->delete();
        AttributeValue::query()->delete();
    }

    public static function importFailed(importFailed $event)
    {
        Log::alert('Import Failed', ['event' => $event]);
    }

    public function chunkSize(): int
    {
        return 20;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
