<?php namespace JetMinds\Job\Models;

use Lang;
use Model;
use BackendAuth;
use Carbon\Carbon;
use ValidationException;
/**
 * Vacancy Model
 */
class Vacancy extends Model
{
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
		'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:jetminds_oms_vacancies'],
		'excerpt' => 'required',
		'requirements' => 'required',
		'expectations' => 'required'
	];

	/**
	 * @var array Attributes that support translation, if available.
	 */
	public $translatable = [
		'title',
		'excerpt',
		'requirements',
		'expectations',
		['slug', 'index' => true]
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
    public $belongsToMany = [];
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

		if (!$user->hasAnyAccess(['jetminds.oms.access_publish'])) {
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
	 * Lists posts for the front end
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
			'search'     => '',
			'published'  => true,
		], $options));

		$searchableFields = [
			'title',
			'slug',
			'excerpt',
			'requirements',
			'expectations'
		];

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

		return $query->paginate($perPage, $page);
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

		if ($item->type == 'all-vacancies') {

			$result = [
				'items' => []
			];

			$vacancies = self::orderBy('title')->get();
			foreach ($vacancies as $vacancy) {
				$vacancyItem = [
					'title' => $vacancies->title,
					'url'   => self::getPostPageUrl($item->cmsPage, $vacancy, $theme),
					'mtime' => $vacancy->updated_at,
				];

				$vacancyItem['isActive'] = $vacancyItem['url'] == $url;

				$result['items'][] = $vacancyItem;
			}
		}

		return $result;
	}
}
