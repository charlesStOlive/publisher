<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBlocNamesTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_bloc_names', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug');
            $table->integer('bloc_type_id')->unsigned();          
            $table->integer('sort_order')->default(0);
                 
            $table->softDeletes();
                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_bloc_names');
    }
}
