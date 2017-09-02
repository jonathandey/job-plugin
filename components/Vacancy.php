<?php namespace JetMinds\Job\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Vacancy as JobVacancy;

class Vacancy extends ComponentBase
{
	/**
	 * @var JetMinds\Job\Models\Vacancy The vacancy model used for display.
	 */
	public $vacancy;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

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
            'categoryPage' => [
                'title'       => 'jetminds.job::lang.components.vacancy.properties.category.title',
                'description' => 'jetminds.job::lang.components.vacancy.properties.category.description',
                'type'        => 'dropdown',
                'default'     => 'job/category',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

	public function onRun()
	{
		$this->vacancy = $this->page['vacancy'] = $this->loadVacancy();
	}

	protected function loadVacancy()
	{
		$slug = $this->property('slug');

		$vacancy = new JobVacancy;

		$vacancy = $vacancy->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
			? $vacancy->transWhere('slug', $slug)
			: $vacancy->where('slug', $slug);

		$vacancy = $vacancy->isPublished()->first();

        /*
         * Add a "url" helper attribute for linking to each category
         */
        if ($vacancy && $vacancy->categories->count()) {
            $vacancy->categories->each(function($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
        }

		return $vacancy;
	}
}
