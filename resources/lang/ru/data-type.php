<?php

use App\Enums\AttributeDataType;

return [
  'description' => [
      AttributeDataType::NUMBER => 'Целое число (10)',
      AttributeDataType::STRING => 'Короткая строка (Это пример)',
      AttributeDataType::RATING => 'Дробное число, рейтинг (2.5)',
      AttributeDataType::PERCENT => 'Дробное число (0.05 = 5%)',
      AttributeDataType::HP => 'Число, л. с. (5)',
      AttributeDataType::MONTHS => 'Число, срок в месяцах (24)',
      AttributeDataType::COUNTRY => 'Код страны в ISO (ru)',
      AttributeDataType::COMMENT => 'Текст любого объема',
      AttributeDataType::PRICE => 'Число, цена (100000)',
  ]
];
