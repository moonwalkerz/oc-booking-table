<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaTableBookings5 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->integer('guests')->default(0)->change();
            $table->string('phone', 191)->nullable()->change();
            $table->string('booking_time', 255)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->integer('guests')->default(null)->change();
            $table->string('phone', 191)->nullable(false)->change();
            $table->string('booking_time', 255)->default('NULL')->change();
        });
    }
}
