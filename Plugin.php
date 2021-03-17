<?php namespace ByteMethod\PasswordPolicy;

use System\Classes\PluginBase;
use ByteMethod\PasswordPolicy\Models\Settings;

class Plugin extends PluginBase {
	public function boot() {
		if (Settings::get('enabled')) {
			self::enablePasswordPolicy();
		}
	}

	private static function enablePasswordPolicy() {
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
					\sprintf('between:%d,255', Settings::get('length', 4)),
				];

				$conditions = [
					'min_number'       => Settings::get('numbers'),
					'min_lower_case'   => Settings::get('lower_case'),
					'min_upper_case'   => Settings::get('upper_case'),
					'min_special_char' => Settings::get('special_char'),
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
