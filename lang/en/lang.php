<?php

return [
	'plugin' => [
		'name' => 'Job',
		'description' => 'This plugin is extendible and easy to use. Created to manage various job pages via October CMS.',
		'author' => 'Jet Minds'
	],

	'access' => [
		'tab' => [
			'general' => 'Job',
			'publish' => 'Job publish'
		],
		'plugin' => 'Access Job',
        'categories' => 'Access Categories',
		'vacancies' => 'Access Vacancies',
		'resumes' => 'Access Resumes',
		'settings' => 'Access Settings',
		'publish' => 'Allowed to publish vacancies',
	],

	'menu' => [
		'primary' => [
			'job' => 'Job'
		],
		'secondary' => [
		    'categories' => 'Categories',
			'vacancies' => 'Vacancies',
			'resumes' => 'Resumes',
			'settings' => 'Settings',
		],
        'item' => [
            'category' => 'Job category',
            'all_categories' => 'All job categories',
            'vacancy' => 'Job vacancy',
            'all_vacancies' => 'All job vacancies'
        ]
	],

	'components' => [
	    'categories' => [
            'details' => [
                'name' => 'Category List',
                'description' => 'Displays a list of job categories on the page.'
            ],
            'properties' => [
                'slug' => [
                    'title' => 'Category Slug',
                    'description' => 'Look up the job category using the supplied slug value. This property is used by the default component partial for marking the currently active category.'
                ],
                'display' => [
                    'title' => 'Display empty categories',
                    'description' => 'Show categories that do not have any vacancies.'
                ],
                'category' => [
                    'title' => 'Category page',
                    'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                ]
            ],
        ],
		'submit_resume' => [
			'details' => [
				'name' => 'Submit Resume',
				'description' => 'Use the component to display a submit form resume a page.',
			],
		],
		'vacancies' => [
			'details' => [
				'name' => 'Vacancies',
				'description' => 'Use the component to display a list of job vacancies on a page.',
			],
			'properties' => [
				'pagination' => [
					'title' => 'Page number',
					'description' => 'This value is used to determine what page the user is on.',
				],
                'filter' => [
                    'title' => 'Category filter',
                    'description' => 'Enter a category slug or URL parameter to filter the vacancies by. Leave empty to show all vacancies.',
                ],
				'per_page' => [
					'title' => 'Vacancies per page',
					'description' => '',
					'validation' => 'Invalid format of the vacancies per page value',
				],
				'no_vacancies' => [
					'title' => 'No vacancies message',
					'description' => 'Message to display in the Organization Management System vacancy list in case if there are no vacancies. This property is used by the default component partial.',
				],
				'order' => [
					'title' => 'Vacancy order',
					'description' => 'Attribute on which the vacancies should be ordered',
				],
                'category' => [
                    'title' => 'Category page',
                    'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                ],
				'vacancy' => [
					'title' => 'Vacancy page',
					'description' => 'Name of the Organization Management System vacancy page file for the "Learn more" links. This property is used by the default component partial.',
				],

			],
		],
		'vacancy' => [
			'details' => [
				'name' => 'Vacancy',
				'description' => 'Use the component to display a Job Vacancy on a page.',
			],
			'properties' => [
				'slug' => [
					'title' => 'Vacancy Slug',
					'description' => 'Look up the OMS vacancy using the supplied slug value.'
				],
                'category' => [
                    'title' => 'Category page',
                    'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                ]
			],
		],
	],

	'controllers' => [
        'categories' => [
            'config' => [
                'form' => [
                    'title' => 'Category',
                    'create' => [
                        'title' => 'Create Category'
                    ],
                    'update' => [
                        'title' => 'Edit Category'
                    ],
                    'preview' => [
                        'title' => 'Preview Category'
                    ],
                ],
                'list' => [
                    'title' => 'Manage Categories'
                ],
                'reorder' => [
                    'title' => 'Categories'
                ],
            ],
            'toolbar' => [
                'buttons' => [
                    'new' => [
                        'label' => 'New Category'
                    ],
                    'delete' => [
                        'label' => 'Delete Selected',
                        'confirm' => 'Are you sure?'
                    ],
                    'reorder' => [
                        'label' => 'Reorder Categories'
                    ],
                ],
            ],
        ],
		'resumes' => [
			'config' => [
				'form' => [
					'name' => 'Resume',
					'create' => [
						'title' => 'Create Resume',
					],
					'update' => [
						'title' => 'Edit Resume',
					],
					'preview' => [
						'title' => 'Preview Resume',
					],
				],
				'list' => [
					'title' => 'Manage Resumes',
				],
			],

			'form' => [
				'preview' => [
					'candidate' => [
						'name' => [
							'label' => 'Candidate',
						],
						'contact' => [
							'label' => 'Contacts',
						],
						'joined' => [
							'label' => 'Joined',
						],
					],
				],
				'breadcrumb' => [
					'label' => 'Resumes'
				],
				'invite' => [
					'form' => [
						'title' => 'Creating an invitation',
						'fields' => [
							'subject' => [
								'label' => 'Subject',
							],
							'date' => [
								'label' => 'Date & Time',
							],
							'message' => [
								'label' => 'Message',
							],
						],
						'button' => [
							'label' => 'Send Invite'
						],
					],
					'flash' => [
						'success' => 'Invite send successfully.',
						'error' => 'Something went wrong, unable to send invite.',
					],
 				],
				'buttons' => [
					'return' => [
						'label' => 'Return to resumes list',
					],
					'invite' => [
						'label' => 'Send invitation'
					]
				],
			],
		],

		'vacancies' => [
			'config' => [
				'form' => [
					'name' => 'Vacancy',
					'create' => [
						'title' => 'Create Vacancy',
					],
					'update' => [
						'title' => 'Edit Vacancy',
					],
					'preview' => [
						'title' => 'Preview Vacancy',
					],
				],
				'list' => [
					'title' => 'Manage Vacancies',
				],
			],
			'form' => [
				'breadcrumb' => [
					'label' => 'Vacancies'
				],
				'buttons' => [
					'create' => [
						'label' => 'Create',
						'indicator' => 'Creating Vacancy...'
					],
					'create&close' => [
						'label' => 'Create & Close',
						'indicator' => 'Creating & Close Vacancy...'
					],
					'save' => [
						'label' => 'Save',
						'indicator' => 'Saving Vacancy...'
					],
					'save&close' => [
						'label' => 'Save & Close',
						'indicator' => 'Saving & Close Vacancy...'
					],
					'delete' => [
						'confirm' => 'Delete this vacancy?',
						'indicator' => 'Deleting Vacancy...'
					],
					'cancel' => [
						'label' => 'Cancel'
					],
					'return' => [
						'label' => 'Return to vacancies list'
					],
				],
			],
			'list' => [
				'buttons' => [
					'new' => [
						'label' => 'New Vacancy',
					],
					'delete' => [
						'label' => 'Delete Selected',
						'confirm' => 'Are you sure?',
					],
				],
			],
		],

		'buttons' => [
			'cancel' => 'Cancel',
			'create' => 'Create',
			'create&close' => 'Create & Close',
			'new' => 'New Item',
			'save' => 'Save',
			'save&close' => 'Save & Close',
			'return' => 'Return to list',
			'or' => 'or',
			'print' => 'Print Resume',
			'send' => 'Send Invite'
		]
	],

	'models' => [
	    'category' => [
	        'columns' => [
                'id' => 'ID',
                'name' => 'Category Name',
                'slug' => 'Category Url',
                'created_at' => 'Created at',
                'updated_at' => 'Updated at',
            ],
            'fields' => [
                'name' => [
                    'label' => 'Category Name',
                ],
                'slug' => [
                    'label' => 'Category Url',
                ],
                'parent' => [
                    'label' => 'Parent Category',
                    'empty' => '-- no parent --'
                ],
                'description' => [
                    'label' => 'Category Description',
                ],
            ],
        ],
		'resume' => [
			'columns' => [
				'id' => [
					'label' => 'ID'
				],
				'position' => [
					'label' => 'Position'
				],
				'first_name' => [
					'label' => 'First Name',
				],
				'last_name' => [
					'label' => 'Last Name',
				],
				'email' => [
					'label' => 'Email',
				],
				'phone' => [
					'label' => 'Phone',
				],
			],
			'fields' => [
				'first_name' => [
					'label' => 'First Name',
				],
				'last_name' => [
					'label' => 'Last Name',
				],
				'email' => [
					'label' => 'Email Address',
				],
				'phone' => [
					'label' => 'Phone Number',
				],
				'position' => [
					'label' => 'Position',
					'placeholder' => 'e.g. "Web Developer"'
				],
				'location' => [
					'label' => 'Location',
					'placeholder' => 'e.g. "London, UK", "New York", "Houston, TX"'
				],

				'category' => [
					'label' => 'Resume category',
					'placeholder' => 'Coming Soon...'
				],
				'note' => [
					'label' => 'Notes'
				],

				// Education
				'education' => [
					'label' => 'Education (Optional)',
					'prompt' => 'Add new education',
					'form' => [
						'fields' => [
							'institute' => [
								'label' => 'Institute',
							],
							'qualification' => [
								'label' => 'Qualification',
							],
							'from' => [
								'label' => 'Date From',
							],
							'to' => [
								'label' => 'Date To',
							],
							'note' => [
								'label' => 'Notes (Optional)',
								'placeholder' => ''
							],
						],
					],
				],

				// Experience
				'experience' => [
					'label' => 'Experience (Optional)',
					'prompt' => 'Add new experience',
					'form' => [
						'fields' => [
							'employer' => [
								'label' => 'Employer'
							],
							'position' => [
								'label' => 'Position'
							],
							'from' => [
								'label' => 'Date From'
							],
							'to' => [
								'label' => 'Date To'
							],
							'note' => [
								'label' => 'Notes (Optional)',
								'placeholder' => 'e.g. "Responsibilities"'
							],
						],
					],
				],

				'language' => [
					'label' => 'Language (Optional)',
					'prompt' => 'Add new language',
					'form' => [
						'fields' => [
							'name' => [
								'label' => 'Language Name',
								'placeholder' => 'Language Name'
							],
							'percentage' => [
								'label' => 'Percentage (%)',
								'placeholder' => 'Percentage %'
							],
						],
					],
				],

				'skill' => [
					'label' => 'Skill (Optional)',
					'prompt' => 'Add new skill',
					'form' => [
						'fields' => [
							'name' => [
								'label' => 'Skill Name',
								'placeholder' => 'Skill Name'
							],
							'percentage' => [
								'label' => 'Percentage (%)',
								'placeholder' => 'Percentage %'
							],
						],
					],
				],

				'tabs' => [
					'contact' => 'Contact Info',
					'general' => 'General Info',
					'detail' => 'Detail Info',
				]

			],
		],
		'vacancy' => [
			'columns' => [
				'id' => 'ID',
				'title' => 'Title',
				'created_at' => 'Created at',
				'updated_at' => 'Updated at',
				'published_at' => 'Published at'
			],
			'fields' => [
				'title' => [
					'label' => 'Title',
				],
				'slug' => [
					'label' => 'Slug',
				],
				'published' => [
					'label' => 'Published',
				],
				'published_at' => [
					'label' => 'Published at',
				],
				'excerpt' => [
					'label' => 'Excerpt',
				],
				'description' => [
					'label' => 'Description',
				],
				'requirements' => [
					'label' => 'Requirements',
					'prompt' => 'Add new requirement',
				],
				'expectations' => [
					'label' => 'Expectations',
					'prompt' => 'Add new expectation',
				],
                'category' => [
                    'label' => 'Category',
                    'empty' => '-- select category --'
                ],

				'tabs' => [
					'general' => 'General',
                    'links' => 'Links',
					'requirements' => 'Requirements',
					'expectations' => 'Expectations',
				]

			],
			'validation' => [
				'published' => 'Please specify the published date'
			],
		],
		'setting' => [
			'redirect' => [
				'switch' => [
					'label' => 'Redirect',
					'comment' => 'Enable redirect page after successful form submit',
				],
				'url' => [
					'label' => 'Redirect Page',
					'comment' => 'Select a page to redirect after a successful CV',
				]
			],
			'notification' => [
				'switch' => [
					'label' => 'Notification',
					'comment' => 'Send mail notification to you when contact form submitted'
				],
				'email' => [
					'label' => 'Notification Email',
					'comment' => 'Email address where notification mail will be sent'
				],
			],

			'auto_response' => [
				'label' => 'Auto Response',
				'comment' => 'Send auto response mail to user who submitted resume'
			],

			'alerts' => [
				'section' => [
					'label' => 'Alerts section',
				],
				'success' => [
					'label' => 'Form success message',
					'comment' => 'After successful form submit this success message will be displayed'
				]
			],
		],
	],

	'settings' => [
		'label' => 'Job Settings',
		'description' => '',
		'category' => 'Job'
	],
];