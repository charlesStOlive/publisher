<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBlocTypesTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_bloc_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->string('compiler');
            $table->boolean('use_icon')->default(0); 
            $table->string('icon')->nullable();                   
            $table->integer('sort_order')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_bloc_types');
    }
}
