<?php

namespace Dashboard\Controllers;

use Dashboard\Models\Projects;
use Dashboard\Models\Github;
use Dashboard\Models\Settings;

class Dashboard extends \Dashboard\Middleware\Controller{
	public function board($filter_status = false){
		$projects = new Projects();
		$settings = new Settings();
		$filters = [
			'status' => $filter_status
		];

		return $this -> new_template() -> make('project.list', [
			'projects' => $projects -> get_projects_list($filters),
			'filters' => $filters,
			'settings' => $settings -> get_settings(),
			'settings_variants' => $settings -> get_settings_variants()
		]);
	}

	public function throw_img($url){
		if(strpos($url, '//') === 0){
			$url = str_replace('//', 'http://', $url);
		}
		$img = file_get_contents($url);
		$format = explode('.', $url);
		$format = $format[count($format) - 1];
		$filename = basename($url);

		switch($format) {
	    case "gif": $ctype="image/gif"; break;
	    case "png": $ctype="image/png"; break;
	    case "jpeg":
	    case "jpg": $ctype="image/jpeg"; break;
	    case "svg": $ctype="image/svg+xml"; break;
	    case "ico": $ctype="image/x-icon"; break;
	    default:
		}

		header('Content-type: ' . $ctype);
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		return $img;
	}

	public function save_setting($setting_name, $value){
		$settings = new Settings();
		return $settings -> save_setting($setting_name, $value);
	}

	public function visibility_project($project_name, $visibility_flag){
		$project_name = urldecode($project_name);
		$visibility_flag = $visibility_flag == 'false' ? false : true;

		$projects = new Projects();
		if($visibility_flag){
			return $projects -> remove_from_hidden_list($project_name);
		}else{
			return $projects -> add_to_hidden_list($project_name);
		}
	}
}