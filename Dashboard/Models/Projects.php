<?php

namespace Dashboard\Models;
use \Dashboard\Utils;

class Projects extends \Dashboard\Middleware\Model{
	protected $path_to_hidden_list_file = PROJECT_FOLDER . '/hidden-list.json';
	protected $utils;

	public function __construct(){
		$this -> utils = new Utils();
	}

	public function get_projects_list($filters){
		$pinfo = new ProjectInfo();
		$folders = FCONF['projects_folders'];
		$projects = [];
		foreach($folders as $folder){
			$projects = array_merge($projects, (function($projects_folder) use ($pinfo){
				$dirs = scandir($projects_folder);
				$projects = [];
				foreach ($dirs as $i => $item) {
					$path = $projects_folder . '/' . $item;
					if(!is_file($path) and $item != '.' and $item != '..'){
						$projects[] = $pinfo -> get_project_info([
							"name" => $item,
							"path" => $path
						]);
					}
				}

				return $projects;
			})($folder));
		}

		$hidden_list = $this -> get_hidden_projects();
		$projects = array_filter($projects, function($project) use($hidden_list){
			return !in_array(strtolower($project['name']), $hidden_list);
		});
		
		usort($projects, function($a, $b){
			return $b['last_update'] - $a['last_update'];
		});

		if(isset($filters['status']) and $filters['status']){
			foreach($projects as $i => $project){
				if(!isset($project['project']['status']) or $project['project']['status'] != $filters['status']){
					unset($projects[$i]);
				}
			}
		}

		return $projects;
	}

	public function get_hidden_projects(){
		return json_decode(file_get_contents($this -> path_to_hidden_list_file));
	}

	public function add_to_hidden_list($project_name){
		$project_name = strtolower($project_name);
		$hidden_list = $this -> get_hidden_projects();
		if(!in_array($project_name, $hidden_list)){
			$hidden_list[] = $project_name;
			return file_put_contents($this -> path_to_hidden_list_file, json_encode($hidden_list, JSON_PRETTY_PRINT));
		}

		return false;
	}

	public function remove_from_hidden_list($project_name){
		$project_name = strtolower($project_name);
		$hidden_list = $this -> get_hidden_projects();
		$pr_inx = array_search($project_name, $hidden_list);
		if($pr_inx !== false){
			array_splice($hidden_list, $pr_inx, 1);
			return file_put_contents($this -> path_to_hidden_list_file, json_encode($hidden_list, JSON_PRETTY_PRINT));
		}

		return false;
	}
}