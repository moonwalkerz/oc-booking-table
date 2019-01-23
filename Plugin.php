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
        return [
            'config' => [
                'label'       => 'Table Booking',
                'icon'        => 'oc-icon-beer',
                'description' => 'Booking Table Config.',
                'class'       => 'MartiniMultimedia\Table\Models\Settings',
          //      'permissions' => ['Table'],
                'order'       => 600
            ]
        ];
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
