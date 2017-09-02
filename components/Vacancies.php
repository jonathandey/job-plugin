<?php namespace JetMinds\Job\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Vacancy as JobVacancy;
use JetMinds\Job\Models\Category as JobCategory;

class Vacancies extends ComponentBase
{
	/**
	 * A collection of vacancies to display
	 * @var Collection
	 */
	public $vacancies;

	/**
	 * Parameter to use for the page number
	 * @var string
	 */
	public $pageParam;

    /**
     * If the post list should be filtered by a category, the model to use.
     * @var Model
     */
    public $category;

	/**
	 * Message to display when there are no messages.
	 * @var string
	 */
	public $noVacanciesMessage;

	/**
	 * Reference to the page name for linking to vacancies.
	 * @var string
	 */
	public $vacancyPage;

    /**
     * Reference to the page name for linking to categories.
     * @var string
     */
    public $categoryPage;

	/**
	 * If the vacancy list should be ordered by another attribute.
	 * @var string
	 */
	public $sortOrder;

    public function componentDetails()
    {
        return [
	        'name'        => 'jetminds.job::lang.components.vacancies.details.name',
	        'description' => 'jetminds.job::lang.components.vacancies.details.description'
        ];
    }

    public function defineProperties()
    {
        return [
	        'pageNumber' => [
		        'title'       => 'jetminds.job::lang.components.vacancies.properties.pagination.title',
		        'description' => 'jetminds.job::lang.components.vacancies.properties.pagination.description',
		        'type'        => 'string',
		        'default'     => '{{ :page }}',
	        ],
            'categoryFilter' => [
                'title'       => 'jetminds.job::lang.components.vacancies.properties.filter.title',
                'description' => 'jetminds.job::lang.components.vacancies.properties.filter.description',
                'type'        => 'string',
                'default'     => ''
            ],
	        'vacanciesPerPage' => [
		        'title'             => 'jetminds.job::lang.components.vacancies.properties.per_page.title',
		        'type'              => 'string',
		        'validationPattern' => '^[0-9]+$',
		        'validationMessage' => 'jetminds.job::lang.components.vacancies.properties.per_page.validation',
		        'default'           => '10',
	        ],
	        'noVacanciesMessage' => [
		        'title'        => 'jetminds.job::lang.components.vacancies.properties.no_vacancies.title',
		        'description'  => 'jetminds.job::lang.components.vacancies.properties.no_vacancies.description',
		        'type'         => 'string',
		        'default'      => 'No vacancies found',
		        'showExternalParam' => false
	        ],
	        'sortOrder' => [
		        'title'       => 'jetminds.job::lang.components.vacancies.properties.order.title',
		        'description' => 'jetminds.job::lang.components.vacancies.properties.order.description',
		        'type'        => 'dropdown',
		        'default'     => 'published_at desc'
	        ],
            'categoryPage' => [
                'title'       => 'jetminds.job::lang.components.vacancies.properties.category.title',
                'description' => 'jetminds.job::lang.components.vacancies.properties.category.description',
                'type'        => 'dropdown',
                'default'     => 'job/category',
                'group'       => 'Links',
            ],
	        'vacancyPage' => [
		        'title'       => 'jetminds.job::lang.components.vacancies.properties.vacancy.title',
		        'description' => 'jetminds.job::lang.components.vacancies.properties.vacancy.description',
		        'type'        => 'dropdown',
		        'default'     => 'job/vacancy',
		        'group'       => 'Links',
	        ]
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

	public function getVacancyPageOptions()
	{
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getSortOrderOptions()
	{
		return JobVacancy::$allowedSortingOptions;
	}

	public function onRun()
	{
		$this->prepareVars();

        $this->category = $this->page['category'] = $this->loadCategory();
		$this->vacancies = $this->page['vacancies'] = $this->listVacancies();

		/*
         * If the page number is not valid, redirect
         */
		if ($pageNumberParam = $this->paramName('pageNumber')) {
			$currentPage = $this->property('pageNumber');

			if ($currentPage > ($lastPage = $this->vacancies->lastPage()) && $currentPage > 1) {
				return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
			}
		}
	}

	protected function prepareVars()
	{
		$this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
		$this->noVacanciesMessage = $this->page['noVacanciesMessage'] = $this->property('noVacanciesMessage');

		/*
		 * Page links
		 */
		$this->vacancyPage = $this->page['vacancyPage'] = $this->property('vacancyPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
	}

	protected function listVacancies()
	{
        $category = $this->category ? $this->category->id : null;

		$vacancies = JobVacancy::with('categories')->listFrontEnd([
			'page'     => $this->property('pageNumber'),
			'sort'     => $this->property('sortOrder'),
			'perPage'  => $this->property('vacanciesPerPage'),
			'search'   => trim(input('search')),
            'category'   => $category
        ]);

		/*
         * Add a "url" helper attribute for linking to each vacancy
         */
		$vacancies->each(function($vacancy) {
			$vacancy->setUrl($this->vacancyPage, $this->controller);

            $vacancy->categories->each(function($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
		});

		return $vacancies;
	}

    protected function loadCategory()
    {
        if (!$slug = $this->property('categoryFilter')) {
            return null;
        }

        $category = new JobCategory;

        $category = $category->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);

        $category = $category->first();

        return $category ?: null;
    }
}
