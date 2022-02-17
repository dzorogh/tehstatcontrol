<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Group;
use App\Models\Year;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    protected function prepareForValidation()
    {
        $attributes = $this->input('filters.attributes', []);

        $this->merge([
            'filters.attributesIds' => $attributes ? array_keys($attributes) : null
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
            filters: {brandsIds: [], attributes: {}, yearId: null, categoryId: 4}
            attributes: {}
            brandsIds: []
            categoryId: 4
            yearId: null
            page: 1
            sort: {type: "title", direction: "asc"}
        */

        return [
            'filters.groupId' => [
                'integer',
                'nullable',
                Rule::exists((new Group())->getTable(), 'id')
            ],
            'filters.groupSlug' => [
                'string',
                'nullable',
                Rule::exists((new Group())->getTable(), 'slug')
            ],
            'filters.yearId' => [
                'integer',
                'nullable',
                Rule::exists((new Year())->getTable(), 'id')
            ],
            'filters.brandsIds' => [
                'array',
                'nullable'
            ],
            'filters.brandsIds.*' => [
                'integer',
                Rule::exists((new Brand())->getTable(), 'id')
            ],
            'filters.categoryId' => [
                'integer',
                'nullable',
                Rule::exists((new Category())->getTable(), 'id')
            ],
            'filters.attributes' => [
                'array',
                'nullable',
            ],
            'filters.attributes.*' => [
                'min:1'
            ],
            'filters.attributesIds.*' => [
                'integer',
                Rule::exists((new Attribute())->getTable(), 'id')
            ],
            'page' => ['required', 'integer', 'min:1'],
            'sort.type' => [Rule::in('title', 'category', 'brand', 'attribute')],
            'sort.direction' => [Rule::in('asc', 'desc')],
            'sort.attributeId' => [
                'required_if:sort.type,attribute',
                Rule::exists((new Attribute())->getTable(), 'id')
            ],

        ];
    }
}
