<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaTableBookings7 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dropColumn('booking_date');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dateTime('booking_date')->nullable()->default('NULL');
        });
    }
}
