<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMmTableBookings2 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dropColumn('booking_time');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->time('booking_time');
        });
    }
}
