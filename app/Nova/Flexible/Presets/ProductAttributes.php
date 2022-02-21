<?php

namespace App\Nova\Flexible\Presets;

use App\Models\Attribute;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class ProductAttributes extends Preset
{

    protected $attributesByYear;

    public function __construct()
    {
        $this->attributesByYear = Attribute::get();
    }

    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        // You can call all available methods on the Flexible field.
        // $field->addLayout(...)
        // $field->button(...)
        // $field->resolver(...)
        // ... and so on.

        $field->button('Добавить аттрибут');
        $field->resolver(\App\Nova\Flexible\Resolvers\ProductAttributesResolver::class);
        $field->help('Go to the "<strong>Page blocks</strong>" Resource in order to add new WYSIWYG block types.');

        $this->attributesByYear->each(function($attribute) use ($field) {
            $field->addLayout($attribute->title, $attribute->id, [
                Textarea::make('Value')
            ]);
        });
    }


}
