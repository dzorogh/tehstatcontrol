<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Attribute
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $data_type
 * @property string $rating_direction
 * @property int $by_year
 * @property int $show_on_chart
 * @property int|null $order
 * @property int|null $group_id
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereByYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereDataType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereRatingDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereShowOnChart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUpdatedAt($value)
 */
	class IdeHelperAttribute {}
}

namespace App\Models{
/**
 * App\Models\AttributeValue
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $value
 * @property int $product_id
 * @property int $attribute_id
 * @property int|null $year_id
 * @property-read \App\Models\Attribute $attribute
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Year|null $year
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue byYear($yearId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereYearId($value)
 */
	class IdeHelperAttributeValue {}
}

namespace App\Models{
/**
 * App\Models\Brand
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 */
	class IdeHelperBrand {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property int|null $main_attribute_id
 * @property-read \App\Models\Attribute|null $main_attribute
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMainAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $slug
 * @property int|null $order
 * @property string|null $description
 * @property string|null $icon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 */
	class IdeHelperGroup {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $slug
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $image
 * @property int $news
 * @method static \Database\Factories\PageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereNews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
	class IdeHelperPage {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property int $brand_id
 * @property int $category_id
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product byAttributes(?array $attributes = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byBrands(?array $brands = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byCategory(?int $categoryId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byYear(?int $yearId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 */
	class IdeHelperProduct {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Year
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $value
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|Year newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Year newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Year query()
 * @method static \Illuminate\Database\Eloquent\Builder|Year whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Year whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Year whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Year whereValue($value)
 */
	class IdeHelperYear {}
}

