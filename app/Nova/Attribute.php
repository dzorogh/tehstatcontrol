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

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Attribute extends Resource
{
    public static $group = 'Статистика';
    public static int $order = 3;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Атрибуты');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Атрибут');
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

    public function subtitle()
    {
        if ($this->group) {
            return "Группа: {$this->group->title}";
        } else {
            return "Без группы";
        }
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
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

            Boolean::make(__('Скрыт'), 'is_hidden')
                ->default(false),

            Text::make(__('Название'), 'title')
                ->rules('required'),

            Text::make(__('Описание'), 'description')
                ->nullable()
                ->hideFromIndex(),

//            Boolean::make(__('Описание'), 'description')
//                ->showOnIndex()
//                ->hideFromDetail()
//                ->hideWhenCreating()
//                ->hideWhenUpdating(),

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
                ->default(false)
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                })
                ->help('Можно редактировать только при создании атрибута'),

            Boolean::make(__('Показывать на графике'), 'show_on_chart')
                ->default(false),

            Boolean::make(__('Показывать фильтр'), 'show_filter')
                ->default(true),

            Number::make(__('Порядок'), 'order')
                ->nullable()
                ->sortable(),

            /*Boolean::make(__('Возможна сортировка'), 'sortable')
                ->nullable()
                ->default(true),*/
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
        return $query->withHidden()->when(empty($request->get('orderByDirection')), function (Builder $query) {
            $query->getQuery()->orders = [];

            return $query->orderBy('group_id')->orderBy('order');
        });
    }
}
