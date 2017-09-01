<?php namespace JetMinds\Job\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Vacancy as listVacancies;

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
	        'vacancyPage' => [
		        'title'       => 'jetminds.job::lang.components.vacancies.properties.page.title',
		        'description' => 'jetminds.job::lang.components.vacancies.properties.page.description',
		        'type'        => 'dropdown',
		        'default'     => 'vacancies/detail',
		        'group'       => 'Links',
	        ]
        ];
    }

	public function getVacancyPageOptions()
	{
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getSortOrderOptions()
	{
		return listVacancies::$allowedSortingOptions;
	}

	public function onRun()
	{
		$this->prepareVars();

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
	}

	protected function listVacancies()
	{
		$vacancies = listVacancies::listFrontEnd([
			'page'     => $this->property('pageNumber'),
			'sort'     => $this->property('sortOrder'),
			'perPage'  => $this->property('vacanciesPerPage'),
			'search'   => trim(input('search'))
		]);

		/*
         * Add a "url" helper attribute for linking to each vacancy
         */
		$vacancies->each(function($vacancy) {
			$vacancy->setUrl($this->vacancyPage, $this->controller);
		});

		return $vacancies;
	}
}
