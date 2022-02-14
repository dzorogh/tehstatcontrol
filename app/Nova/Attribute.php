<?php

namespace App\Nova;

use App\Enums\AttributeDataType;
use App\Enums\RatingDirection;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Attribute extends Resource
{
    public static $group = 'Статистика';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Аттрибуты');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Аттрибут');
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Attribute::class;

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
                ->rules('required'),

            BelongsTo::make(__('Группа данных'), 'group', Group::class)
                ->nullable()
                ->sortable(),

            Select::make(__('Тип данных'), 'data_type')
                ->options([
                    AttributeDataType::NUMBER => 'Число',
                    AttributeDataType::STRING => 'Строка',
                    AttributeDataType::MONTHS => 'Длительность в месяцах',
                    AttributeDataType::PERCENT => 'Процент',
                    AttributeDataType::HP => 'Лошадиные силы',
                    AttributeDataType::COUNTRY => 'Страна',
                    AttributeDataType::COMMENT => 'Текстовый комментарий',
                    AttributeDataType::PRICE => 'Стоимость',
                    AttributeDataType::RATING => 'Рейтинг',
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Select::make(__('Направление рейтинга'), 'rating_direction')
                ->options([
                    RatingDirection::ASC => '↓ Чем больше, тем лучше',
                    RatingDirection::DESC => '↑ Чем больше, тем хуже',
                ])
                ->displayUsingLabels()
                ->default('asc'),

            Boolean::make(__('Разделять по годам'), 'by_year')
                ->default(false),

            Boolean::make(__('Показывать на графике'), 'show_on_chart')
                ->default(false),

            Number::make(__('Порядок'), 'order')
                ->nullable()
                ->sortable(),

            Boolean::make(__('Возможна сортировка'), 'sortable')
                ->nullable()
                ->default(true),
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

    /**
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->when(empty($request->get('orderByDirection')), function (Builder $query) {
            $query->getQuery()->orders = [];

            return $query->orderBy('group_id')->orderBy('order');
        });
    }
}
