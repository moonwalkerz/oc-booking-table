<?php namespace MartiniMultimedia\Table\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMmTableBookings extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->dateTime('booking_date')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_table_bookings', function($table)
        {
            $table->date('booking_date')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
