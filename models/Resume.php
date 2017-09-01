<?php namespace JetMinds\Job\Models;

use Model;

/**
 * Resume Model
 */
class Resume extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jetminds_job_resumes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

	/**
	 * @var array Jsonable fields
	 */
	protected $jsonable = [
		'resume_education',
		'resume_experience',
		'resume_language',
		'resume_skill'
	];

	/**
	 * @var array The attributes that should be casted to native types.
	 */
	protected $casts = [
		'is_invite' => 'boolean',
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
}
