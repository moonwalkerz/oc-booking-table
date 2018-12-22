<?php namespace MartiniMultimedia\Table;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
    			'MartiniMultimedia\Table\Components\BookingForm' => 'bookingform'
    	];
    }

    public function registerSettings()
    {
    }

/*
     public function registerFormWidgets()
{
    return [

        'Backend\FormWidgets\DatePicker' => [
            'label' => 'Date picker',
            'code'  => 'datepicker'
        ],

        'Backend\FormWidgets\TimePicker' => [
            'label' => 'Time picker',
            'code'  => 'timepicker'
        ]
    ];
}
*/
}
