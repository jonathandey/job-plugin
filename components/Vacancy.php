<?php namespace JetMinds\Job\Components;

use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Vacancy as detailVacancy;
class Vacancy extends ComponentBase
{
	/**
	 * @var JetMinds\Job\Models\Vacancy The vacancy model used for display.
	 */
	public $vacancy;

    public function componentDetails()
    {
        return [
	        'name'        => 'jetminds.job::lang.components.vacancy.details.name',
	        'description' => 'jetminds.job::lang.components.vacancy.details.description'
        ];
    }

    public function defineProperties()
    {
        return [
	        'slug' => [
		        'title'       => 'jetminds.job::lang.components.vacancy.properties.slug.title',
		        'description' => 'jetminds.job::lang.components.vacancy.properties.slug.description',
		        'default'     => '{{ :slug }}',
		        'type'        => 'string'
	        ],
        ];
    }

	public function onRun()
	{
		$this->vacancy = $this->page['vacancy'] = $this->loadVacancy();
	}

	protected function loadVacancy()
	{
		$slug = $this->property('slug');

		$vacancy = new detailVacancy;

		$vacancy = $vacancy->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
			? $vacancy->transWhere('slug', $slug)
			: $vacancy->where('slug', $slug);

		$vacancy = $vacancy->isPublished()->first();

		return $vacancy;
	}
}
