<?php namespace Waka\Publisher\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDocumentsBlocsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_publisher_blocs_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('bloc_id')->unsigned();
            $table->integer('document_id')->unsigned();
            $table->primary(['bloc_id', 'document_id' ], 'bloc_document');
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_publisher_blocs_documents');
    }
}
