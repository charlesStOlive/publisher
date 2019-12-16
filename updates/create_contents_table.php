<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContentsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_contents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('data');
            $table->integer('sector_id')->unsigned()->nullable();
            $table->integer('bloc_id')->unsigned()->nullable();
            $table->unique(['sector_id', 'bloc_id']);
                                                
            $table->softDeletes();
                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_contents');
    }
}
