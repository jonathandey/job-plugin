<?php namespace JetMinds\Job;

use Backend;
use Controller;
use System\Classes\PluginBase;
use JetMinds\Job\Models\Vacancy;
use JetMinds\Job\Models\Category;
use Event;

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
            'name'        => 'jetminds.job::lang.plugin.name',
            'description' => 'jetminds.job::lang.plugin.description',
            'author'      => 'jetminds.job::lang.plugin.author',
            'icon'        => 'icon-briefcase',
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
        /*
		 * Register menu items for the RainLab.Pages plugin
		 */
        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'job-category'              => 'jetminds.job::lang.menu.item.category',
                'all-job-categories'        => 'jetminds.job::lang.menu.item.all_categories',
                'job-vacancy'               => 'jetminds.job::lang.menu.item.vacancy',
                'all-job-vacancies'         => 'jetminds.job::lang.menu.item.all_vacancies',
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function($type) {
            if ($type == 'job-category' || $type == 'all-job-categories') {
                return Category::getMenuTypeInfo($type);
            }
            elseif ($type == 'job-vacancy' || $type == 'all-job-vacancies') {
                return Vacancy::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            if ($type == 'job-category' || $type == 'all-job-categories') {
                return Category::resolveMenuItem($item, $url, $theme);
            }
            elseif ($type == 'job-vacancy' || $type == 'all-job-vacancies') {
                return Vacancy::resolveMenuItem($item, $url, $theme);
            }
        });

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'JetMinds\Job\Components\SubmitResume'  => 'jobResume',
            'JetMinds\Job\Components\Vacancy'       => 'jobVacancy',
            'JetMinds\Job\Components\Vacancies'     => 'jobVacancies',
            'JetMinds\Job\Components\Categories'    => 'jobCategories',
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
                'tab'   => 'jetminds.job::lang.access.tab.general',
                'label' => 'jetminds.job::lang.access.plugin'
            ],
            'jetminds.job.access_categories' => [
	            'tab'   => 'jetminds.job::lang.access.tab.general',
	            'label' => 'jetminds.job::lang.access.categories'
            ],
            'jetminds.job.access_vacancies' => [
                'tab'   => 'jetminds.job::lang.access.tab.general',
                'label' => 'jetminds.job::lang.access.vacancies'
            ],
            'jetminds.job.access_resumes' => [
	            'tab'   => 'jetminds.job::lang.access.tab.general',
	            'label' => 'jetminds.job::lang.access.resumes'
            ],
            'jetminds.job.access_settings' => [
	            'tab'   => 'jetminds.job::lang.access.tab.general',
	            'label' => 'jetminds.job::lang.access.settings'
            ],
            'jetminds.job.access_publish' => [
	            'tab' => 'jetminds.job::lang.access.tab.publish',
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
                    'categories' => [
                        'label'       => 'jetminds.job::lang.menu.secondary.categories',
                        'icon'        => 'icon-briefcase',
                        'url'         => Backend::url('jetminds/job/categories'),
                        'permissions' => ['jetminds.job.access_categories']
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
