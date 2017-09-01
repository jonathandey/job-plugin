<?php namespace JetMinds\Job;

use Backend;
use System\Classes\PluginBase;

/**
 * Job Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Job',
            'description' => 'Create excellent job listings and get detailed summaries.',
            'author'      => 'JetMinds',
            'icon'        => 'icon-leaf',
            'homepage'    => 'https://github.com/jetmindsgroup/job-plugin'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'JetMinds\Job\Components\SubmitResume'  => 'submitResume',
            'JetMinds\Job\Components\Vacancies'     => 'listVacancies',
            'JetMinds\Job\Components\Vacancy'       => 'detailVacancy',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'jetminds.job.access_plugin' => [
                'tab'   => 'jetminds.job::lang.access.tab',
                'label' => 'jetminds.job::lang.access.plugin'
            ],
            'jetminds.job.access_vacancies' => [
	            'tab'   => 'jetminds.job::lang.access.tab',
	            'label' => 'jetminds.job::lang.access.vacancies'
            ],
            'jetminds.job.access_resumes' => [
	            'tab'   => 'jetminds.job::lang.access.tab',
	            'label' => 'jetminds.job::lang.access.resumes'
            ],
            'jetminds.job.access_settings' => [
	            'tab'   => 'jetminds.job::lang.access.tab',
	            'label' => 'jetminds.job::lang.access.settings'
            ],
            'jetminds.job.access_publish' => [
	            'tab' => 'jetminds.job::lang.access.pub',
	            'label' => 'jetminds.job::lang.access.publish'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'job' => [
                'label'       => 'jetminds.job::lang.menu.primary.job',
                'url'         => Backend::url('jetminds/job/resumes'),
                'iconSvg'     => 'plugins/jetminds/job/assets/images/job-search.svg',
                'permissions' => ['jetminds.job.access_job'],
                'order'       => 310,
                'sideMenu' => [
	                'resumes' => [
		                'label'       => 'jetminds.job::lang.menu.secondary.resumes',
		                'icon'        => 'icon-inbox',
		                'url'         => Backend::url('jetminds/job/resumes'),
		                'permissions' => ['jetminds.job.access_resumes'],
	                ],
	                'vacancies' => [
		                'label'       => 'jetminds.job::lang.menu.secondary.vacancies',
		                'icon'        => 'icon-briefcase',
		                'url'         => Backend::url('jetminds/job/vacancies'),
		                'permissions' => ['jetminds.job.access_vacancies']
	                ],
	                'settings' => [
		                'label'       => 'jetminds.job::lang.menu.secondary.settings',
		                'icon'        => 'icon-cog',
		                'url'         => Backend::url('system/settings/update/jetminds/job/form'),
		                'permissions' => ['jetminds.job.access_settings']
	                ],
                ],
            ],
        ];
    }

	public function registerMailTemplates()
	{
		return [
			'jetminds.job::mail.invite'         => 'Job | Invite message',
			'jetminds.job::mail.response'       => 'Job | Notification message',
			'jetminds.job::mail.notification'   => 'Job | Auto response message',
		];
	}

	public function registerSettings()
	{
		return [
			'form' => [
				'label'       => 'jetminds.job::lang.settings.label',
				'description' => 'jetminds.job::lang.settings.description',
				'category'    => 'jetminds.job::lang.settings.category',
				'icon'        => 'icon-cog',
				'class'       => 'JetMinds\Job\Models\Settings',
				'order'       => 500,
				'permissions' => ['jetminds.job.access_settings'],
			]
		];
	}
}
