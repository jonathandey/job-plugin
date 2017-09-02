<?php namespace JetMinds\Job\Models;

use Db;
use Url;
use App;
use Str;
use Html;
use Lang;
use Model;
use Markdown;
use BackendAuth;
use ValidationException;
use Carbon\Carbon;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
/**
 * Vacancy Model
 */
class Vacancy extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jetminds_job_vacancies';

	public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

	/*
	 * Validation
	 */
	public $rules = [
		'title' => 'required',
		'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:jetminds_job_vacancies'],
		'excerpt' => 'required',
		'requirements' => 'required',
		'expectations' => 'required'
	];

	/**
	 * @var array Attributes that support translation, if available.
	 */
	public $translatable = [
		'title',
        ['slug', 'index' => true],
		'excerpt',
		'requirements',
		'expectations'
	];

	/**
	 * The attributes that should be mutated to dates.
	 * @var array
	 */
	protected $dates = ['published_at'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
	    'title',
	    'slug',
	    'excerpt',
	    'requirements',
	    'expectations',
    ];

	/**
	 * @var array Jsonable fields
	 */
	protected $jsonable = [
		'requirements',
		'expectations'
	];

	/**
	 * The attributes on which the post list can be ordered
	 * @var array
	 */
	public static $allowedSortingOptions = [
		'title asc' => 'Title (ascending)',
		'title desc' => 'Title (descending)',
		'created_at asc' => 'Created (ascending)',
		'created_at desc' => 'Created (descending)',
		'updated_at asc' => 'Updated (ascending)',
		'updated_at desc' => 'Updated (descending)',
		'published_at asc' => 'Published (ascending)',
		'published_at desc' => 'Published (descending)',
		'random' => 'Random'
	];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'categories' => [
            'JetMinds\Job\Models\Category',
            'table' => 'jetminds_job_vacancies_categories',
            'order' => 'name'
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

	public $preview = null;

	/**
	 * Limit visibility of the published-button
	 * @return void
	 */
	public function filterFields($fields, $context = null)
	{
		if (!isset($fields->published, $fields->published_at)) {
			return;
		}

		$user = BackendAuth::getUser();

		if (!$user->hasAnyAccess(['jetminds.job.access_publish'])) {
			$fields->published->hidden = true;
			$fields->published_at->hidden = true;
		}
		else {
			$fields->published->hidden = false;
			$fields->published_at->hidden = false;
		}
	}

	public function afterValidate()
	{
		if ($this->published && !$this->published_at) {
			throw new ValidationException([
				'published_at' => Lang::get('jetminds.job::lang.models.vacancy.validation.published')
			]);
		}
	}

    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id'   => $this->id,
            'slug' => $this->slug,
        ];

        if (array_key_exists('categories', $this->getRelations())) {
            $params['category'] = $this->categories->count() ? $this->categories->first()->slug : null;
        }

        //expose published year, month and day as URL parameters
        if ($this->published) {
            $params['year'] = $this->published_at->format('Y');
            $params['month'] = $this->published_at->format('m');
            $params['day'] = $this->published_at->format('d');
        }

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    //
    // Scopes
    //

    public function scopeIsPublished($query)
    {
        return $query
            ->whereNotNull('published')
            ->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<', Carbon::now())
            ;
    }

    /**
     * Lists vacancies for the front end
     * @param  array $options Display options
     * @return self
     */
    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'       => 1,
            'perPage'    => 30,
            'sort'       => 'created_at',
            'categories' => null,
            'category'   => null,
            'search'     => '',
            'published'  => true,
        ], $options));

        $searchableFields = ['title', 'slug', 'excerpt'];

        if ($published) {
            $query->isPublished();
        }

        /*
         * Sorting
         */
        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $_sort) {

            if (in_array($_sort, array_keys(self::$allowedSortingOptions))) {
                $parts = explode(' ', $_sort);
                if (count($parts) < 2) {
                    array_push($parts, 'desc');
                }
                list($sortField, $sortDirection) = $parts;
                if ($sortField == 'random') {
                    $sortField = Db::raw('RAND()');
                }
                $query->orderBy($sortField, $sortDirection);
            }
        }

        /*
         * Search
         */
        $search = trim($search);
        if (strlen($search)) {
            $query->searchWhere($search, $searchableFields);
        }

        /*
         * Categories
         */
        if ($categories !== null) {
            if (!is_array($categories)) $categories = [$categories];
            $query->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('id', $categories);
            });
        }

        /*
         * Category, including children
         */
        if ($category !== null) {
            $category = Category::find($category);

            $categories = $category->getAllChildrenAndSelf()->lists('id');
            $query->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('id', $categories);
            });
        }

        return $query->paginate($perPage, $page);
    }

    /**
     * Allows filtering for specifc categories
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  array                     $categories List of category ids
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterCategories($query, $categories)
    {
        return $query->whereHas('categories', function($q) use ($categories) {
            $q->whereIn('id', $categories);
        });
    }

    //
    // Menu helpers
    //

    /**
     * Handler for the pages.menuitem.getTypeInfo event.
     * Returns a menu item type information. The type information is returned as array
     * with the following elements:
     * - references - a list of the item type reference options. The options are returned in the
     *   ["key"] => "title" format for options that don't have sub-options, and in the format
     *   ["key"] => ["title"=>"Option title", "items"=>[...]] for options that have sub-options. Optional,
     *   required only if the menu item type requires references.
     * - nesting - Boolean value indicating whether the item type supports nested items. Optional,
     *   false if omitted.
     * - dynamicItems - Boolean value indicating whether the item type could generate new menu items.
     *   Optional, false if omitted.
     * - cmsPages - a list of CMS pages (objects of the Cms\Classes\Page class), if the item type requires a CMS page reference to
     *   resolve the item URL.
     * @param string $type Specifies the menu item type
     * @return array Returns an array
     */
    public static function getMenuTypeInfo($type)
    {
        $result = [];

        if ($type == 'job-vacancy') {

            $references = [];
            $vacancies = self::orderBy('title')->get();
            foreach ($vacancies as $vacancy) {
                $references[$vacancy->id] = $vacancy->title;
            }

            $result = [
                'references'   => $references,
                'nesting'      => false,
                'dynamicItems' => false
            ];
        }

        if ($type == 'all-job-vacancies') {
            $result = [
                'dynamicItems' => true
            ];
        }

        if ($result) {
            $theme = Theme::getActiveTheme();

            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];

            foreach ($pages as $page) {
                if (!$page->hasComponent('jobVacancy')) {
                    continue;
                }

                /*
                 * Component must use a categoryPage filter with a routing parameter and post slug
                 * eg: categoryPage = "{{ :somevalue }}", slug = "{{ :somevalue }}"
                 */
                $properties = $page->getComponentProperties('jobVacancy');
                if (!isset($properties['categoryPage']) || !preg_match('/{{\s*:/', $properties['slug'])) {
                    continue;
                }

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    /**
     * Handler for the pages.menuitem.resolveItem event.
     * Returns information about a menu item. The result is an array
     * with the following keys:
     * - url - the menu item URL. Not required for menu item types that return all available records.
     *   The URL should be returned relative to the website root and include the subdirectory, if any.
     *   Use the Url::to() helper to generate the URLs.
     * - isActive - determines whether the menu item is active. Not required for menu item types that
     *   return all available records.
     * - items - an array of arrays with the same keys (url, isActive, items) + the title key.
     *   The items array should be added only if the $item's $nesting property value is TRUE.
     * @param \RainLab\Pages\Classes\MenuItem $item Specifies the menu item.
     * @param \Cms\Classes\Theme $theme Specifies the current theme.
     * @param string $url Specifies the current page URL, normalized, in lower case
     * The URL is specified relative to the website root, it includes the subdirectory name, if any.
     * @return mixed Returns an array. Returns null if the item cannot be resolved.
     */
    public static function resolveMenuItem($item, $url, $theme)
    {
        $result = null;

        if ($item->type == 'job-vacancy') {
            if (!$item->reference || !$item->cmsPage)
                return;

            $category = self::find($item->reference);
            if (!$category)
                return;

            $pageUrl = self::getProductPageUrl($item->cmsPage, $category, $theme);
            if (!$pageUrl)
                return;

            $pageUrl = Url::to($pageUrl);

            $result = [];
            $result['url'] = $pageUrl;
            $result['isActive'] = $pageUrl == $url;
            $result['mtime'] = $category->updated_at;
        }
        elseif ($item->type == 'all-job-vacancies') {
            $result = [
                'items' => []
            ];

            $vacancies = self::orderBy('title')->get();
            foreach ($vacancies as $vacancy) {
                $vacancyItem = [
                    'title' => $vacancy->title,
                    'url'   => self::getProductPageUrl($item->cmsPage, $vacancy, $theme),
                    'mtime' => $vacancy->updated_at,
                ];

                $vacancyItem['isActive'] = $vacancyItem['url'] == $url;

                $result['items'][] = $vacancyItem;
            }
        }

        return $result;
    }

    /**
     * Returns URL of a product page.
     */
    protected static function getProductPageUrl($pageCode, $category, $theme)
    {
        $page = CmsPage::loadCached($theme, $pageCode);
        if (!$page) return;

        $properties = $page->getComponentProperties('jobVacancy');
        if (!isset($properties['slug'])) {
            return;
        }

        /*
         * Extract the routing parameter name from the category filter
         * eg: {{ :someRouteParam }}
         */
        if (!preg_match('/^\{\{([^\}]+)\}\}$/', $properties['slug'], $matches)) {
            return;
        }

        $paramName = substr(trim($matches[1]), 1);
        $url = CmsPage::url($page->getBaseFileName(), [$paramName => $category->slug]);

        return $url;
    }
}
