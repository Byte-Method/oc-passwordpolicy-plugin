<?php namespace ByteMethod\PasswordPolicy\Models;

use Model;

class Settings extends Model {

	use \October\Rain\Database\Traits\Validation;

	public $implement = [
		\System\Behaviors\SettingsModel::class,
	];

	// A unique code
	public $settingsCode = 'bytemethod_passwordpolicy_settings';

	// Reference to field configuration
	public $settingsFields = 'fields.yaml';

	public $rules = [
		'enabled'      => ['boolean'],
		'length'       => ['required_if:enabled,true', 'numeric', 'min:4', 'max:255'],
		'numbers'      => ['required_if:enabled,true', 'numeric', 'min:0', 'max:255'],
		'upper_case'   => ['required_if:enabled,true', 'numeric', 'min:0', 'max:255'],
		'lower_case'   => ['required_if:enabled,true', 'numeric', 'min:0', 'max:255'],
		'special_char' => ['required_if:enabled,true', 'numeric', 'min:0', 'max:255'],
	];
}