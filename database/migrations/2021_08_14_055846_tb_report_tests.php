<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbReportTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_tests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name");
            $table->string("position");
            $table->string("email");
            $table->string("phone");
            $table->dateTime("time_start")->nullable();
            $table->dateTime("time_end")->nullable();
            $table->string("remark")->nullable();
        });

        Schema::create("report_test_answers", function(Blueprint $table) {
           $table->id();
           $table->timestamps();
           $table->bigInteger("question_id");
           $table->string("question");
           $table->string("answer")->nullable();
           $table->string("correct")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_tests');
    }
}
