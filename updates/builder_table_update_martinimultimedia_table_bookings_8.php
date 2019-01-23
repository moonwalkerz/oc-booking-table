<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaTableBookings8 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dateTime('booking_date')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dropColumn('booking_date');
        });
    }
}
