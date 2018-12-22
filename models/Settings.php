<?php namespace MartiniMultimedia\Table\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'martinimultimedia_table_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}