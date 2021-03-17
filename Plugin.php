<?php namespace ByteMethod\PasswordPolicy;

use System\Classes\PluginBase;
use ByteMethod\PasswordPolicy\Models\BackendPasswordPolicySettings;

class Plugin extends PluginBase {
	public function boot() {
		if (BackendPasswordPolicySettings::get('enabled')) {
			BackendPasswordPolicySettings::enable();
		}
	}
}
