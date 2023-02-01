<?php

namespace Dashboard\Models;

class Settings extends \Dashboard\Middleware\Model {
	protected $path_to_settings_file = PROJECT_FOLDER . '/settings.json';

	public function get_settings(){
		return json_decode(file_get_contents($this -> path_to_settings_file), true);
	}

	public function save_setting(String $setting_name, String $value){
		$settings = $this -> get_settings();
		if(!isset($settings[$setting_name])){
			return false;
		}
		$settings[$setting_name] = $value;
		return file_put_contents($this -> path_to_settings_file, json_encode($settings, JSON_PRETTY_PRINT));
	}

	public function get_settings_variants(){
		$data = [
			'color-schema' => json_decode(file_get_contents(PROJECT_FOLDER . '/Resources/css/color-schema/index.json'))
		];

		return $data;
	}
}