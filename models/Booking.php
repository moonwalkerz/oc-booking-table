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

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_table_bookings';


    public function setBookingDateAttribute($value)
       {
  //      $this->attributes['booking_date'] = Carbon::createFromFormat('d/m/Y', $value); 
    }
}