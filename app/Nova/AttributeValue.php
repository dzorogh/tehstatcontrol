<?php

namespace App\Nova;

use App\Enums\AttributeDataType;
use App\Nova\Filters\ByAttribute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttributeValue extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AttributeValue::class;

    public static $group = 'Статистика';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Значения аттрибутов');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Значение аттрибута');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        $result = $this->attribute->title;

        if ($this->year) {
            $result .= ' за ' . $this->year->value . 'г. ';
        }

        if ($this->attributable) {

            if ($this->attributable_type === 'brand') {
                $result .= ' у бренда ' . $this->attributable->title;
            }

            if ($this->attributable_type === 'product') {
                $result .= ' у товара ' . $this->attributable->title;
            }
        }

        return $result;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'value',
    ];

    public static $perPageViaRelationship = 50;

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            MorphTo::make(__('Товар/Бренд'), 'attributable', Product::class)->types([
                Product::class,
                Brand::class
            ])->sortable()->searchable(),

            BelongsTo::make(__('Атрибут'), 'attribute', Attribute::class)->hideWhenCreating()->hideWhenUpdating(),

            Select::make(__('Атрибут'), 'attribute_id')->options(function () {
                return array_filter(\App\Models\Attribute::query()
                    ->orderBy('group_id', 'desc')
                    ->orderBy('order', 'asc')
                    ->get()
                    ->map(function (\App\Models\Attribute $attribute) {
                        $attribute->title = $attribute->title . " [" . __('data-type.description.' . $attribute->data_type) . "]";
                        return $attribute;
                    })
                    ->pluck('title', 'id')
                    ->toArray());
            })->hideFromIndex()->hideFromDetail(),

            BelongsTo::make(__('Год'), 'year', Year::class)
                ->nullable()
                ->sortable()
                ->required()
                ->rules([
                    Rule::requiredIf($this->attribute && $this->attribute->by_year)
                ])
                ->if(['attribute_id'], function ($attributeId) {
                    if ($attributeId) {
                        $attribute = \App\Models\Attribute::whereId($attributeId)->first();

                        if ($attribute) {
                            return $attribute->by_year;
                        }
                    }
                }),

            Textarea::make(__('Значение'), 'value')
                ->sortable()
                ->alwaysShow()
                ->showOnIndex()
                ->help(
                    (function () {

                    })()
                )
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\ByAttribute,
            new Filters\ByYear
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
