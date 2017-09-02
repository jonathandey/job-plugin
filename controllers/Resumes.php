<?php namespace JetMinds\Job\Controllers;

use Mail;
use Validator;
use BackendMenu;
use ValidationException;
use Backend\Facades\Backend;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Redirect;
use JetMinds\Job\Models\Resume;

/**
 * Resumes Back-end Controller
 */
class Resumes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

	public $bodyClass = 'compact-container layout-resume';

	public $requiredPermissions = ['jetminds.oms.access_resumes'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('JetMinds.Job', 'job', 'resumes');
	    SettingsManager::setContext('JetMinds.Job', 'job');

	    $this->addCss("/plugins/jetminds/job/assets/css/backend.css");
    }

	/**
	 * Send Invite
	 */
	public function onInviteSubmit(){

		$formValidationRules = [
			'subject' => ['required'],
			'date' => ['required'],
			'message' => ['required']
		];

		$validator = Validator::make(post(), $formValidationRules);
		// Validate
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}

		$record = Resume::find(post('id'));

		if($record){

			$vars = [
				'invite_date' => (post('date')),
				'invite_message' => (post('message'))
			];

			Mail::send('jetminds.job::mail.invite', $vars, function($invite) {
				$invite->to(post('email_to'), post('name_to'));
				$invite->subject(post('subject'));
			});

			$record->is_invite = true;

			$record->save();

			Flash::success(e(trans('jetminds.job::lang.controllers.resumes.form.invite.flash.success')));
		}
		else {
			Flash::error(e(trans('jetminds.job::lang.controllers.resumes.form.invite.flash.error')));
		}
	}

}
