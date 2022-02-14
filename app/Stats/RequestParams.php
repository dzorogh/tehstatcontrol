<?php

namespace App\Stats;

use JetBrains\PhpStorm\ArrayShape;

class RequestParams
{
    /**
     * @var int[]
     */
    public ?array $brands = [];

    /**
     * @var int|null
     */
    public ?int $categoryId = null;

    /**
     * @var int|null
     */
    public ?int $yearId = null;

    /**
     * @var int|null
     */
    public ?int $groupId = null;

    /**
     * @var array|null
     */
    public ?array $attributes = [];

    public function setBrands(array $brands = null)
    {
        $this->brands = $brands;
    }

    public function setCategory(int $categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function setYear(int $yearId = null)
    {
        $this->yearId = $yearId;
    }

    public function setAttributes(array $attributes = null)
    {
        $this->attributes = $attributes;
    }

    public function setGroup(mixed $groupId)
    {
        $this->groupId = $groupId;
    }

    #[ArrayShape([
        'group_id' => "int|null",
        'category_id' => "int|null",
        'year_id' => "int|null",
        'brands' => "int[]|null",
        'attributes' => "array|null"])
    ]
    public function all()
    {
        return [
            'group_id' => $this->groupId,
            'category_id' => $this->categoryId,
            'year_id' => $this->yearId,
            'brands' => $this->brands,
            'attributes' => $this->attributes
        ];
    }
}
