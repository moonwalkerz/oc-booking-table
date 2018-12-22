<?php namespace MartiniMultimedia\Table\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class User extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    /*
    public $requiredPermissions = [
        'Telegram' 
    ];
*/
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MartiniMultimedia.Table', 'menu-item-bookings', 'side-menu-item2');
    }
}