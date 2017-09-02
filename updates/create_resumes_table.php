<?php namespace JetMinds\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateResumesTable extends Migration
{
    public function up()
    {
        Schema::create('jetminds_job_resumes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->string('first_name')->nullable();
	        $table->string('last_name')->nullable();
	        $table->string('email')->nullable();
	        $table->string('phone')->nullable();
	        $table->string('position')->nullable();
	        $table->longText('location')->nullable();
	        $table->string('resume_category')->nullable();
	        $table->string('resume_education')->nullable();
	        $table->longText('education_note')->nullable();
	        $table->string('resume_experience')->nullable();
	        $table->longText('experience_note')->nullable();
	        $table->string('resume_language')->nullable();
	        $table->string('resume_skill')->nullable();
	        $table->longText('resume_note')->nullable();
	        $table->boolean('is_invite')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jetminds_job_resumes');
    }
}
