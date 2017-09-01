<?php namespace JetMinds\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVacanciesTable extends Migration
{
    public function up()
    {
        Schema::create('jetminds_job_vacancies', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->string('title')->nullable();
	        $table->string('slug')->index();
	        $table->text('excerpt')->nullable();
	        $table->longText('description')->nullable();
	        $table->string('requirements')->nullable();
	        $table->string('expectations')->nullable();
	        $table->boolean('published')->default(false);
	        $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jetminds_job_vacancies');
    }
}
