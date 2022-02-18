<?php

namespace Database\Seeders;

use App\Enums\AttributeDataType;
use App\Enums\RatingDirection;
use App\Models\Page;
use App\Models\Attribute;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $faker = Factory::create();

        Artisan::call('create:admin');

        DB::table('users')->updateOrInsert(
            [
                'email' => env('EDITOR_EMAIL')
            ],
            [
                'name' => 'Editor',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => User::roleEditor,
            ]
        );
        echo "Created user (editor):" . env('EDITOR_EMAIL') . "\r\n";

        DB::table('users')->updateOrInsert(
            [
                'email' => env('USER_EMAIL')
            ],
            [
                'name' => 'User',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => User::roleUser,
            ]
        );
        echo "Created user (user):" . env('USER_EMAIL') . "\r\n";

        DB::table('pages')->updateOrInsert(
            [
                'slug' => 'about'
            ],
            [
                'title' => 'Об организации',
                'content' => $faker->paragraphs(4, true)
            ]
        );
        echo "Created page: about\r\n";

        DB::table('pages')->updateOrInsert(
            [
                'slug' => 'contacts'
            ],
            [
                'title' => 'Контакты',
                'content' => $faker->paragraphs(4, true)
            ]
        );
        echo "Created page: contacts\r\n";

        DB::table('pages')->updateOrInsert(
            [
                'slug' => 'privacy-policy'
            ],
            [
                'title' => 'Политика конфиденциальности',
                'content' => $faker->paragraphs(4, true)
            ]
        );
        echo "Created page: privacy-policy\r\n";

        $resultPages = Page::factory(5)->create(['news' => true]);

        foreach ($resultPages as $page) {
            echo 'Created page: ' . $page->slug . "\r\n";
        }


        $statsInfoId = DB::table('stats_groups')->insertGetId([
            'slug' => 'info',
            'title' => 'Общая информация',
            'description' => 'В данном разделе собраны данные о стране производства брендов техники, производящейся и экспортируемой в Российскую Федерацию.',
            'icon' => 'InformationCircleIcon',
        ]);
        $statsPricingId = DB::table('stats_groups')->insertGetId([
            'slug' => 'pricing',
            'title' => 'Ценообразование',
            'description' => 'Информация в данном разделе основана на общедоступной информации в сети интернет.',
            'icon' => 'PresentationChartLineIcon'
        ]);
        $statsServiceId = DB::table('stats_groups')->insertGetId([
            'slug' => 'service',
            'title' => 'Сервисная статистика',
            'description' => 'Информация в данном разделе основана на статистике сервисных центров.',
            'icon' => 'CogIcon'
        ]);
        $statsAdvantagesId = DB::table('stats_groups')->insertGetId([
            'slug' => 'advantages',
            'title' => 'Преимущества и недостатки',
            'description' => 'Информация в данном разделе основана на общедоступной информации в сети интернет, результатах краш-тестов и статистике сервисных центров.',
            'icon' => 'ThumbUpIcon'
        ]);
        $statsRatingId = DB::table('stats_groups')->insertGetId([
            'slug' => 'rating',
            'title' => 'Рейтинги',
            'description' => 'Рейтинг - средняя потребительская оценка, формирующаяся из всех факторов и критериев по рассматриваемому бренду. Информация в данном разделе основана на общедоступной информации в сети Интернет.',
            'icon' => 'StarIcon'
        ]);

//        ///
//
//        $statsCountryId = DB::table('stats_attributes')->insertGetId([
//            'title' => 'Страна производства',
//            'data_type' => AttributeDataType::COUNTRY,
//            'by_year' => false,
//            'show_on_chart' => false,
//            'group_id' => $statsInfoId,
//        ]);
//
//        $statsGuarantyId = DB::table('stats_attributes')->insertGetId([
//            'title' => 'Гарантия',
//            'data_type' => AttributeDataType::MONTHS,
//            'by_year' => true,
//            'show_on_chart' => true,
//            'group_id' => $statsServiceId,
//        ]);
//
//        $statsPowerId = DB::table('stats_attributes')->insertGetId([
//            'title' => 'Мощность',
//            'data_type' => AttributeDataType::HP,
//            'by_year' => false,
//            'show_on_chart' => false,
//        ]);
//
//        $statsCylindersId = DB::table('stats_attributes')->insertGetId([
//            'title' => 'Количество цилиндров',
//            'data_type' => AttributeDataType::NUMBER,
//            'by_year' => false,
//            'show_on_chart' => false,
//        ]);
//
//        $statsDepreciationId = DB::table('stats_attributes')->insertGetId([
//            'title' => 'Износ в первый год эксплуатации',
//            'data_type' => AttributeDataType::PERCENT,
//            'by_year' => true,
//            'show_on_chart' => true,
//            'rating_direction' => RatingDirection::DESC,
//            'group_id' => $statsServiceId,
//        ]);
//
//        ///
//
//        $statsYear2020 = DB::table('stats_years')->insertGetId([
//            'value' => 2020
//        ]);
//
//        $statsYear2021 = DB::table('stats_years')->insertGetId([
//            'value' => 2021
//        ]);
//
//        //
//
//        $statsATVId = DB::table('stats_categories')->insertGetId([
//            'title' => 'Квадроциклы',
//            'main_attribute_id' => $statsPowerId
//        ]);
//
//        $statsBoatMotorId = DB::table('stats_categories')->insertGetId([
//            'title' => 'Лодочные моторы',
//            'main_attribute_id' => $statsPowerId
//        ]);
//
//        $statsPVCBoats = DB::table('stats_categories')->insertGetId([
//            'title' => 'Лодки ПВХ',
//        ]);
//
//        //
//
//        $statsBrand1Id = DB::table('stats_brands')->insertGetId([
//            'title' => 'Honda',
//        ]);
//
//        $statsBrand2Id = DB::table('stats_brands')->insertGetId([
//            'title' => 'Tohatsu',
//        ]);
//
//        $statsBrand3Id = DB::table('stats_brands')->insertGetId([
//            'title' => 'Yamaha',
//        ]);
//
//        //
//
//        $statsProduct1Id = DB::table('stats_products')->insertGetId([
//            'title' => '4х-тактный лодочный мотор HONDA BF100D',
//            'brand_id' => $statsBrand1Id,
//            'category_id' => $statsBoatMotorId
//        ]);

//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '100',
//            'product_id' => $statsProduct1Id,
//            'attribute_id' => $statsPowerId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '4',
//            'product_id' => $statsProduct1Id,
//            'attribute_id' => $statsCylindersId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '6',
//            'product_id' => $statsProduct1Id,
//            'attribute_id' => $statsGuarantyId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.5',
//            'product_id' => $statsProduct1Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2020
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.1',
//            'product_id' => $statsProduct1Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2021
//        ]);
//
//        //
//
//        $statsProduct2Id = DB::table('stats_products')->insertGetId([
//            'title' => '4х-тактный лодочный мотор HONDA BF10D B',
//            'brand_id' => $statsBrand1Id,
//            'category_id' => $statsATVId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '10',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsPowerId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '1',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsCylindersId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '24',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsGuarantyId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.5',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2020,
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.1',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2021
//        ]);
//
//        //
//
//        $statsProduct2Id = DB::table('stats_products')->insertGetId([
//            'title' => '4х-тактный лодочный мотор HONDA BF40D C',
//            'brand_id' => $statsBrand3Id,
//            'category_id' => $statsBoatMotorId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '40',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsPowerId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsCylindersId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '6',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsGuarantyId
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.5',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2020
//        ]);
//
//        DB::table('stats_attribute_values')->insertGetId([
//            'value' => '2.1',
//            'product_id' => $statsProduct2Id,
//            'attribute_id' => $statsRatingId,
//            'year_id' => $statsYear2021
//        ]);
    }
}
