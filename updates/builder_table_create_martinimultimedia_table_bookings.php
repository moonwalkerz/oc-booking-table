<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMmTableBookings extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_table_bookings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('name');
            $table->integer('guests');
            $table->string('phone');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_table_bookings');
    }
}
