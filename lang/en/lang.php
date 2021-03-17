<?php return [
	'plugin' => [
		'name' => 'Backend Password Policy',
		'description' => 'Manage rules for enforcing strong passwords.',
	],
	'permission' => [
		'manage' => 'Manage password policies',
	],
	'backend' => [
		'settings' => [
			'label' => 'Backend Password Policy',
			'description' => 'Configure the backend password policy.',
			'form' => [
				'enabled' => [
					'label'   => 'Enable',
					'comment' => 'Enforce a password policy for backend users.',
				],
				'length' => [
					'label'   => 'Length',
					'comment' => 'Require a minimum password length.',
				],
				'numbers' => [
					'label'   => 'Numbers',
					'comment' => 'Minimum quantity of numbers.',
				],
				'special_char' => [
					'label'   => 'Special Characters',
					'comment' => 'Minimum quantity of special characters.',
				],
				'lower_case' => [
					'label'   => 'Lower Case',
					'comment' => 'Minimum quantity of lower case letters.',
				],
				'upper_case' => [
					'label'   => 'Upper Case',
					'comment' => 'Minimum quantity of upper case letters.',
				],
			]
		],
	],
];