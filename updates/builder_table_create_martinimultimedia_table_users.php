<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaTableUsers extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_table_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('dialog_id')->nullable()->unsigned();
            $table->integer('step_count')->nullable()->unsigned();
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->text('variables')->nullable();
            $table->integer('tg_id')->nullable();
            $table->integer('chat_id')->nullable();
            $table->string('user_name', 191)->nullable();
            $table->boolean('blocked')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_table_users');
    }
}
