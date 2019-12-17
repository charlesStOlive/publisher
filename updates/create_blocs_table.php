<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBlocsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_blocs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('ready')->default(0);

            $table->integer('document_id')->unsigned()->nullable();

            $table->integer('bloc_type_id')->unsigned();  

            $table->integer('sort_order')->default(0);
                                    
            $table->softDeletes();
                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_blocs');
    }
}
