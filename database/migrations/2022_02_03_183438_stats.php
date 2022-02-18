<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Stats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->string('slug');

            $table->integer('order')->nullable();

            $table->text('description')->nullable();
            $table->string('icon')->nullable();
        });

        Schema::create('stats_attributes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');

            $table->string('data_type')->default('string');
            $table->string('rating_direction')->default('asc');
            $table->boolean('by_year')->default(false);
            $table->boolean('show_on_chart')->default(false);
            $table->boolean('filterable')->default(false);
            $table->boolean('sortable')->default(true);

            $table->integer('order')->nullable();

            $table->foreignId('group_id')->nullable()->constrained('stats_groups');
        });

        Schema::create('stats_years', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('value');
        });

        Schema::create('stats_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');

            $table->foreignId('main_attribute_id')->nullable()->constrained('stats_attributes');
        });

        Schema::create('stats_brands', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
        });

        Schema::create('stats_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');

            $table->foreignId('brand_id')
                ->constrained('stats_brands')
                ->onDelete('cascade');;

            $table->foreignId('category_id')
                ->constrained('stats_categories')
                ->onDelete('cascade');;
        });

        Schema::create('stats_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('value');

            $table->foreignId('attribute_id')
                ->constrained('stats_attributes')
                ->onDelete('cascade');

            $table->foreignId('year_id')
                ->nullable()
                ->constrained('stats_years')
                ->onDelete('cascade');

            $table->morphs('attributable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stats_attribute_values');
        Schema::drop('stats_products');
        Schema::drop('stats_brands');
        Schema::drop('stats_categories');
        Schema::drop('stats_years');
        Schema::drop('stats_attributes');
        Schema::drop('stats_groups');
    }
}
