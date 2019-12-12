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

            $table->integer('document_id')->nullable();

            $table->integer('data_id')->nullable();
            $table->string('data_type')->nullable();  

            $table->integer('personalisation')->nullable();

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
