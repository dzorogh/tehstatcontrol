<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
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
    public function title() {
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
        'id',
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

            BelongsTo::make(__('Аттрибут'), 'attribute', Attribute::class)->sortable(),

            BelongsTo::make(__('Год'), 'year', Year::class)->sortable(),

            Text::make(__('Значение'), 'value')->sortable(),

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
        return [];
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
