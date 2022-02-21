<?php

namespace App\Nova\Flexible\Resolvers;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Year;
use App\Nova\Flexible\Layouts\ProductAttributeValueLayout;
use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class ProductAttributesResolver implements ResolverInterface
{
    /**
     * get the field's value
     *
     * @param mixed $resource
     * @param string $attribute
     * @param Whitecube\NovaFlexibleContent\Layouts\Collection $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {
        $attributeByYear = collect(Attribute::getAttributesByYear());

        /** @var Product $product */
        $product = $resource;

        $values = $product->values;

        $result = $attributeByYear->map(function ($item) use ($values) {
//            $layout = $layouts->find($block->name);

//            if(!$layout) return;

            $layout = new ProductAttributeValueLayout();

            $value = $values->where('attribute_id', $item['attribute_id'])->where('year_id', $item['year_id'])->first();

            return $layout->duplicateAndHydrate('attribute' . $item['attribute_id'] . 'year' . $item['year_id'], [
                'value' => $value ? $value->value : '',
                'year' => $item['year'],
                'attribute' => $item['attribute']
            ]);

        })->filter();

//        dd($result);
        return $result;
    }

    /**
     * Set the field's value
     *
     * @param mixed $model
     * @param string $attribute
     * @param Illuminate\Support\Collection $groups
     * @return string
     */
    public function set($model, $attribute, $values)
    {
//        $class = get_class($model);
//
//        $class::saved(function ($model) use ($groups) {
//            $blocks = $groups->map(function($group, $index) {
//                return [
//                    'name' => $group->name(),
//                    'value' => json_encode($group->getAttributes()),
//                    'order' => $index
//                ];
//            });
//
//            // This is a quick & dirty example, syncing the models is probably a better idea.
//            $model->blocks()->delete();
//            $model->blocks()->createMany($blocks);
//        });
    }
}
