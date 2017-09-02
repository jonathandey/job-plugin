<?php namespace JetMinds\Job\Components;

use Db;
use App;
use Request;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JetMinds\Job\Models\Category as JobCategory;

class Categories extends ComponentBase
{
    /**
     * @var Collection A collection of categories to display
     */
    public $categories;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    /**
     * @var string Reference to the current category slug.
     */
    public $currentCategorySlug;

    public function componentDetails()
    {
        return [
            'name'        => 'jetminds.job::lang.components.categories.details.name',
            'description' => 'jetminds.job::lang.components.categories.details.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'jetminds.job::lang.components.categories.properties.slug.title',
                'description' => 'jetminds.job::lang.components.categories.properties.slug.description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'displayEmpty' => [
                'title'       => 'jetminds.job::lang.components.categories.properties.display.title',
                'description' => 'jetminds.job::lang.components.categories.properties.display.description',
                'type'        => 'checkbox',
                'default'     => 0
            ],
            'categoryPage' => [
                'title'       => 'jetminds.job::lang.components.categories.properties.category.title',
                'description' => 'jetminds.job::lang.components.categories.properties.category.description',
                'type'        => 'dropdown',
                'default'     => 'job/category',
                'group'       => 'Links',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('slug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->categories = $this->page['categories'] = $this->loadCategories();
    }

    protected function loadCategories()
    {
        $categories = JobCategory::orderBy('name');

        if (!$this->property('displayEmpty')) {
            $categories->whereExists(function($query) {
                $prefix = Db::getTablePrefix();
                $query
                    ->select(Db::raw(1))
                    ->from('jetminds_job_vacancies_categories')
                    ->join('jetminds_job_vacancies', 'jetminds_job_vacancies.id', '=', 'jetminds_job_vacancies_categories.vacancy_id')
                    ->whereNotNull('jetminds_job_vacancies.published')
                    ->where('jetminds_job_vacancies.published', '=', 1)
                    ->whereRaw($prefix.'jetminds_job_categories.id = '.$prefix.'jetminds_job_vacancies_categories.category_id')
                ;
            });
        }

        $categories = $categories->getNested();

        /*
         * Add a "url" helper attribute for linking to each category
         */
        return $this->linkCategories($categories);
    }

    protected function linkCategories($categories)
    {
        return $categories->each(function($category) {
            $category->setUrl($this->categoryPage, $this->controller);

            if ($category->children) {
                $this->linkCategories($category->children);
            }
        });
    }
}
