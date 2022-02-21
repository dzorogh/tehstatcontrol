<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Group extends Resource
{
    public static $group = 'Статистика';
    public static int $order = 1;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Группы данных');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Группа данных');
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Group::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

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
            Text::make(__('Название'), 'title')
                ->sortable()
                ->rules('required'),

            Slug::make(__('URL'), 'slug')
                ->from('title')
                ->hideFromIndex()
                ->sortable()
                ->rules('required'),

            Number::make(__('Порядок'), 'order')
                ->nullable(),

            Textarea::make(__('Описание'), 'description')
                ->hideFromIndex()
                ->nullable(),

            Select::make(__('Иконка'), 'icon')
                ->rules('required')
                ->options([
                    'InformationCircleIcon' => 'InformationCircleIcon',
                    'PresentationChartLineIcon' => 'PresentationChartLineIcon',
                    'CogIcon' => 'CogIcon',
                    'ThumbUpIcon' => 'ThumbUpIcon',
                    'StarIcon' => 'StarIcon',
                ])
                ->rules('required'),

            HasMany::make(__('Атрибуты'), 'attributes', Attribute::class)
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
