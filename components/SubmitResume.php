<?php namespace JetMinds\Job\Components;

use Mail;
use Input;
use Flash;
use Redirect;
use Validator;
use ValidationException;
use Backend\Facades\Backend;
use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Resume;
use JetMinds\Job\Models\Settings;

class SubmitResume extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'jetminds.job::lang.components.submit_resume.details.name',
            'description' => 'jetminds.job::lang.components.submit_resume.details.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

	public function onRun()
	{
		$this->addCss("/plugins/jetminds/job/assets/css/frontend.css");
		$this->addJs("/plugins/jetminds/job/assets/js/form/repeater.min.js");
		$this->addJs("/plugins/jetminds/job/assets/js/form/form.js");
	}

	public function onSubmitResume()
	{
		$data = post();

		$rules = [
			'first_name' => 'required|min:5',
			'last_name' => 'required|min:5',
			'email' => 'required|email',
			'phone' => 'required',
			'position' => 'required',
			'location' => 'required',
		];

		$validator = Validator::make($data, $rules);

		if ($validator->fails()) {

			throw new ValidationException($validator);

		} else {

			$this->onFormSubmit();

			if(Settings::get('redirect_to_page',false) && !empty(Settings::get('redirect_to_url','')))
				return Redirect::to(Settings::get('redirect_to_url'));
			else{
				Flash::success(Settings::get('alert_success'));
				return ['#form_flash_message' => $this->renderPartial('@flashMessage.htm')];
			}
		}
	}

	public function onFormSubmit()
	{
    	$resume = new Resume;

		$resume->first_name = Input::get('first_name');
		$resume->last_name = Input::get('last_name');
		$resume->email = Input::get('email');
		$resume->phone = Input::get('phone');
		$resume->position = Input::get('position');
		$resume->location = Input::get('location');

		$resume->resume_category = Input::get('resume_category');
		$resume->resume_education = Input::get('resume_education');
		$resume->education_note = Input::get('education_note');
		$resume->resume_experience = Input::get('resume_experience');
		$resume->experience_note = Input::get('experience_note');
		$resume->resume_language = Input::get('resume_language');
		$resume->resume_skill = Input::get('resume_skill');
		$resume->resume_note = Input::get('resume_note');

		$resume->save();

		if(Settings::get('notification',false) && !empty(Settings::get('notification_email','')))
			$this->onSendNotification($resume->id);

		if(Settings::get('auto_response',false))
			$this->onSendAutoResponse();
	}

	/**
	 * Send notification email
	 */
	protected function onSendNotification($resume_id){
		$url_resume = Backend::url('jetminds/job/resumes/preview/'.$resume_id);
		$vars = [
			'url_message'   => $url_resume,
			'first_name'    => Input::get('first_name'),
			'last_name'     => Input::get('last_name'),
			'email'         => Input::get('email'),
		];

		Mail::send('jetminds.job::mail.notification', $vars, function($message) use ($vars) {
			$message->to(Settings::get('notification_email'));
			$message->replyTo($vars['email'], $vars['first_name'], $vars['last_name']);
		});
	}

	/**
	 * Send Auto Reply
	 */
	protected function onSendAutoResponse(){

		$vars = [
			'first_name'    => Input::get('first_name'),
			'last_name'     => Input::get('last_name'),
			'email'         => Input::get('email')
		];

		Mail::send('jetminds.job::mail.response', $vars, function($message) {

			$message->to(post('email'), post('first_name', 'last_name'));

		});

	}


}
