<?php namespace JetMinds\Job\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Vacancies Back-end Controller
 */
class Vacancies extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

	public $requiredPermissions = ['jetminds.oms.access_vacancies'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('JetMinds.Job', 'job', 'vacancies');
    }
}
