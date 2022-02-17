<?php

namespace App\Stats;

use JetBrains\PhpStorm\ArrayShape;

class RequestFilters
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
        'groupId' => "int|null",
        'categoryId' => "int|null",
        'yearId' => "int|null",
        'brandsIds' => "int[]|null",
        'attributes' => "array|null"])
    ]
    public function all()
    {
        return [
            'groupId' => $this->groupId,
            'categoryId' => $this->categoryId,
            'yearId' => $this->yearId,
            'brandsIds' => $this->brands,
            'attributes' => $this->attributes
        ];
    }
}
