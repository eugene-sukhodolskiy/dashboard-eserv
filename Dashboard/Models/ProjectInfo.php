<?php

namespace Dashboard\Models;
use \Dashboard\Utils;
use \Dashboard\PipeLine;

class ProjectInfo extends \Dashboard\Middleware\Model{
	protected $utils;
	public function __construct(){
		$this -> utils = new Utils();
	}

	public function get_project_info($project_data){
		$project = new PipeLine();

		$project -> pipe(function($data){
			return $this -> search_info_files($data);
		});

		// INIT
		$project -> pipe(function($data){
			$data['last_update'] = filemtime($data['path']);
			$data['project'] = !isset($data['info_files']['dashboard.json']) ? [] : $data['info_files']['dashboard.json'];
			return $data;
		});

		// Default type
		$project -> pipe(function($data){
			if(!isset($data['project']['type']) or !$data['project']['type']){
				$data['project']['type'] = 'documents';
			}
			return $data;
		});

		// IF dashboard.json not exists
		$project -> pipe(function($data){
			return $this -> spare_project_data($data);
		});

		$project -> pipe(function($data){
			return $this -> normalize_author_info($data);
		});

		$project -> pipe(function($data){
			return $this -> search_favicon($data);
		});

		$project -> pipe(function($data){
			return $this -> search_git_info($data);
		});

		$project -> pipe(function($data){
			return $this -> git_legacy($data);
		});

		$project -> pipe(function($data){
			return $this -> normalize_git_link($data);
		});

		$project -> pipe(function($data){
			return $this -> about_project_files_struct($data);
		});

		$project -> pipe(function($data){
			return $this -> normalize_author_info($data);
		});

		$project -> pipe(function($data){
			if(!isset($data['project']['links']) or !is_array($data['project']['links'])){
				$data['project']['links'] = [];
			}
			return $data;
		});

		return $project -> through_the_pipe($project_data);
	}

	public function get_project_json_file($path_to_dir, $filename){
		$project_json_file = $path_to_dir . '/' . $filename;
		if(!file_exists($project_json_file)){
			return null;
		}

		return json_decode(file_get_contents($project_json_file), true);
	}

	public function get_dashboard_json_file($path_to_dir){
		$project_file = $path_to_dir . '/dashboard.json';
		$project_file_legacy = $path_to_dir . '/project.json';
		if(!file_exists($project_file)){
			if(!file_exists($project_file_legacy)){
				return null;
			}
			$project_file = $project_file_legacy;
		}

		$project_json = json_decode(file_get_contents($project_file), true);
		return $project_json;
	}

	public function search_info_files($data){
		$dashboard_json = $this -> get_dashboard_json_file($data['path']);
		if(isset($dashboard_json['path_to_project'])){
			if(file_exists($dashboard_json['path_to_project'])){
				$new_path = $dashboard_json['path_to_project'];
			}else{
				$new_path = $data['path'] . '/' . $dashboard_json['path_to_project'];
			}
			$dashboard_json = $this -> get_dashboard_json_file($new_path);
			$data['path'] = $new_path;
		}

		$data['info_files'] = [
			'dashboard.json' => $dashboard_json,
			'package.json' => $this -> get_project_json_file($data['path'], 'package.json'),
			'bower.json' => $this -> get_project_json_file($data['path'], 'bower.json')
		];

		return $data;
	}

	public function spare_project_data($data){
		if(!isset($data['project']['name'])){
			if(isset($data['info_files']['package.json']) and isset($data['info_files']['package.json']['name'])){
				$data['project']['name'] = $data['info_files']['package.json']['name'];
			}else if(isset($data['info_files']['bower.json']) and isset($data['info_files']['bower.json']['name'])){
				$data['project']['name'] = $data['info_files']['bower.json']['name'];
			}
		}
		if(!isset($data['project']['ver'])){
			if(isset($data['info_files']['package.json']) and isset($data['info_files']['package.json']['version'])){
				$data['project']['ver'] = $data['info_files']['package.json']['version'];
			}else if(isset($data['info_files']['bower.json']) and isset($data['info_files']['bower.json']['version'])){
				$data['project']['ver'] = $data['info_files']['bower.json']['version'];
			}
		}
		if(!isset($data['project']['author']) or !$data['project']['author']){
			if(isset($data['info_files']['package.json']) and isset($data['info_files']['package.json']['author'])){
				$data['project']['author'] = $data['info_files']['package.json']['author'];
			}else if(isset($data['info_files']['bower.json']) and isset($data['info_files']['bower.json']['author'])){
				$data['project']['author'] = $data['info_files']['bower.json']['author'];
			}
		}
		if(!isset($data['project']['repository'])){
			if(isset($data['info_files']['package.json']) and isset($data['info_files']['package.json']['repository'])){
				$data['project']['repository'] = $data['info_files']['package.json']['repository'];
			}
		}
		if(!isset($data['project']['description'])){
			if(isset($data['info_files']['package.json']) and isset($data['info_files']['package.json']['description'])){
				$data['project']['description'] = $data['info_files']['package.json']['description'];
			}else if(isset($data['info_files']['bower.json']) and isset($data['info_files']['bower.json']['description'])){
				$data['project']['description'] = $data['info_files']['bower.json']['description'];
			}
		}
		return $data;
	}

	public function search_favicon($data){
		if(!isset($data['project']['favicon']) or !$data['project']['favicon']){
			$favicon = $this -> utils() -> deep_search_file($data['path'], 'favicon.');

			if(isset($favicon) and $favicon){
				$path_to_fav = explode($data['name'], $favicon['path']);
				$path_to_fav[1] = '//' . $data['name'] . $path_to_fav[1];
				unset($path_to_fav[0]);
				$data['project']['favicon'] = implode($data['name'], $path_to_fav);
				$data['project']['favicon_path'] = $favicon['path'];
			}
		}
		return $data;
	}

	public function search_git_info($data){
		if(!isset($data['project']['git_url']) or !$data['project']['git_url']){
			$git_conf = $data['path'] . DIRECTORY_SEPARATOR . '.git/config';
			if(file_exists($git_conf)){
				$git_conf_file = file_get_contents($git_conf);
				$sep = '.git';
				list($git_conf_file) = explode($sep, $git_conf_file);
				list(, $git_url) = explode('url = ', $git_conf_file);
				$data['project']['git_url'] = $git_url;
			}
		}

		return $data;
	}

	public function about_project_files_struct($data){
		$data['project']['scan'] = [
			'list' => ['all' => $this -> utils -> scandirs($data['path'])],
			'fsize' => 0
		];

		if(is_array($data['project']['scan']['list']['all'])){
			$filtered = array_filter($data['project']['scan']['list']['all'], function($item){
				return strpos($item, DIRECTORY_SEPARATOR . '.') === false;
			});
		}else{
			$filtered = [];
		}

		$data['project']['scan']['list']['filtered'] = [
			'folders' => [],
			'files' => [],
			'total' => []
		];

		foreach($filtered as $i => $item){
			if(is_file($item)){
				$data['project']['scan']['list']['filtered']['files'][] = $item;
				$data['project']['scan']['fsize'] += filesize($item);
			}else{
				$data['project']['scan']['list']['filtered']['folders'][] = $item;
			}
		}
		$data['project']['scan']['list']['filtered']['total'] = [
			'folders' => count($data['project']['scan']['list']['filtered']['folders']),
			'files' => count($data['project']['scan']['list']['filtered']['files'])
		];

		$data['project']['scan']['fsize'] = $this -> utils -> filesize_formatted($data['project']['scan']['fsize']);
		return $data;
	}

	public function normalize_author_info($data){
		$authors = isset($data['project']['authors']) ? $data['project']['authors'] : [];

		if(isset($data['project']['author']) and !isset($data['project']['authors'])){
			$a = $data['project']['author'];
			
			if(is_array($a)){
				if(!isset($a['name'])){
					foreach($a as $a_single){
						$authors[] = author_arr($a_single);
					}
				}else{
					$authors[] = author_arr($a);
				}
			}else if(is_string($data['project']['author']) and strlen($data['project']['author'])){
				$authors[] = parse_author_string($data['project']['author']);
			}
		}

		$data['project']['authors'] = $authors;
		return $data;
	}

	public function git_legacy($data){
		$p = $data['project'];
		if(isset($p['repository']) and is_array($p['repository']) and count($p['repository'])){
			return $data;
		}

		if(isset($p['git_url'])){
			$data['project']['repository'] = [
				"type" => "git",
				"url" => $p['git_url']
			];
		}
		return $data;
	}

	public function normalize_git_link($data){
		if(isset($data['project']['repository']) and isset($data['project']['repository']['url'])){
			list(, $url) = explode('http', $data['project']['repository']['url']);
			$data['project']['repository']['url'] = 'http' . $url;
		}

		return $data;
	}
}



function parse_author_string($author_string){
	$author = [
		'name' => '',
		'url' => '',
		'email' => ''
	];

	if(strpos($author_string, '<') !== false){
		$parsing = explode('<', $author_string);
		$parsing = explode('>', $parsing[1]);
		$author['email'] = $parsing[0];
	}

	if(strpos($author_string, '(') !== false){
		$parsing = explode('(', $author_string);
		$parsing = explode(')', $parsing[1]);
		$author['url'] = $parsing[0];
	}

	$author['name'] = trim(str_replace("({$author['url']})", "", str_replace("<{$author['email']}>", '', $author_string)));

	return $author;
}

function author_arr($a){
	$author = [
		'name' => isset($a['name']) ? $a['name'] : '',
		'email' => isset($a['email']) ? $a['email'] : '',
		'url' => isset($a['url']) ? $a['url'] : ''
	];

	return $author;
}