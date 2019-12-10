<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTextBlocsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_text_blocs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
                                                
            $table->softDeletes();
                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_text_blocs');
    }
}
