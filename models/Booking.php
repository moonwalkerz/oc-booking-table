<?php namespace MartiniMultimedia\Table\Models;

use Model;
use Carbon\Carbon;
/**
 * Model
 */
class Booking extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','created_at','updated_at','booking_date'];

    /*
     * Validation
     */
    public $rules = [
    ];


    public function setBookingDateAttribute($value)
    {
        list($d,$m,$y)=explode('/',$value);
        $this->attributes['booking_date'] =$y."-".$m."-".$d;
    }

    public function getBookingDateAttribute($value)
    {
        list($d,$m,$y)=explode('-',$value);
        return $d."/".$m."/".$y;
    }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_table_bookings';

    public function scopeNextBookings($query) {
        return $query->where('booking_date','>=', Carbon::now())->orderby('booking_date','disc');
    }
    

}