<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBlocTextsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_bloc_texts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('data');                          
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_bloc_texts');
    }
}
