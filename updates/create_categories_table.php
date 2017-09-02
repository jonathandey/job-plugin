<?php namespace JetMinds\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('jetminds_job_categories', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->timestamps();
        });
        Schema::create('jetminds_job_vacancies_categories', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('vacancy_id')->unsigned();
            $table->integer('category_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jetminds_job_categories');
        Schema::dropIfExists('jetminds_job_vacancies_categories');
    }
}
