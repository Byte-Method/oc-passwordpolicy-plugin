<?php namespace ByteMethod\PasswordPolicy\Models;

use Lang;
use Model;
use Validator;
use Backend\Models\User;

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

	public static function enable() {
		// minimum numbers
		Validator::extend('min_number', function($attribute, $condition, $parameters){
			if( preg_match_all('/[0-9]/', $condition) < $parameters[0] ) return false;
			return true;
		}, Lang::get('bytemethod.passwordpolicy::validation.custom.min_number') );
		Validator::replacer('min_number', function ($message, $attribute, $rule, $parameters) {
			return \str_replace(':min', $parameters[0], $message);
		});

		// minimum upper case
		Validator::extend('min_upper_case', function($attribute, $condition, $parameters){
			if( preg_match_all('/[A-Z]/', $condition) < $parameters[0] ) return false;
			return true;
		}, Lang::get('bytemethod.passwordpolicy::validation.custom.min_upper_case') );
		Validator::replacer('min_upper_case', function ($message, $attribute, $rule, $parameters) {
			return \str_replace(':min', $parameters[0], $message);
		});

		// minimum lower case
		Validator::extend('min_lower_case', function($attribute, $condition, $parameters){
			if( preg_match_all('/[a-z]/', $condition) < $parameters[0] ) return false;
			return true;
		}, Lang::get('bytemethod.passwordpolicy::validation.custom.min_lower_case') );
		Validator::replacer('min_lower_case', function ($message, $attribute, $rule, $parameters) {
			return \str_replace(':min', $parameters[0], $message);
		});

		// minimum special characters
		Validator::extend('min_special_char', function($attribute, $condition, $parameters){
			if( preg_match_all('/[\W]/', $condition) < $parameters[0] ) return false;
			return true;
		}, Lang::get('bytemethod.passwordpolicy::validation.custom.min_special_char'));
		Validator::replacer('min_special_char', function ($message, $attribute, $rule, $parameters) {
			return \str_replace(':min', $parameters[0], $message);
		});

		// extend backend user model
		User::extend(function($model) {
			$model->bindEvent('model.beforeValidate', function() use ($model) {
				$rules = [
					'required:create',
					\sprintf('between:%d,255', self::get('length', 4)),
				];

				$conditions = [
					'min_number'       => self::get('numbers'),
					'min_lower_case'   => self::get('lower_case'),
					'min_upper_case'   => self::get('upper_case'),
					'min_special_char' => self::get('special_char'),
				];

				foreach ($conditions as $rule => $condition) {
					if ($condition !== null) $rules[] = \sprintf('%s:%d', $rule, $condition);
				}

				$rules[] = 'confirmed';

				$model->rules['password'] = $rules;
			});
		});
	}
}