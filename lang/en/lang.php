<?php

return [
	'plugin' => [
		'name' => 'Jobs',
		'description' => 'Create amazing job listings vacancies and let guests apply to them',
		'author' => 'Jet Minds'
	],

	'access' => [
		'tab' => 'Job',
		"pub" => 'Job Publish',
		'plugin' => 'Access Job',
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
			'vacancies' => 'Vacancies',
			'resumes' => 'Resumes',
			'settings' => 'Settings',
		],
	],

	'components' => [
		'submit_resume' => [
			'details' => [
				'name' => 'Submit Resume',
				'description' => 'No description provided yet...',
			],
		],
		'vacancies' => [
			'details' => [
				'name' => 'Vacancies',
				'description' => 'No description provided yet...',
			],
			'properties' => [
				'pagination' => [
					'title' => 'Page number',
					'description' => 'This value is used to determine what page the user is on.',
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
				'page' => [
					'title' => 'Vacancy page',
					'description' => 'Name of the Organization Management System vacancy page file for the "Learn more" links. This property is used by the default component partial.',
				],

			],
		],
		'vacancy' => [
			'details' => [
				'name' => 'Vacancy',
				'description' => 'No description provided yet...',
			],
			'properties' => [
				'slug' => [
					'title' => 'Vacancy Slug',
					'description' => 'Look up the OMS vacancy using the supplied slug value.'
				],
			],
		],
	],

	'controllers' => [
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

				'tabs' => [
					'general' => 'General',
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