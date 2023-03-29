<?php

namespace Dashboard;

class Utils{
	private $clear_cache_state_flag = false;

	public function deep_search_file($dir, $file_to_search, $exactly = false){
		if(!is_dir($dir)){
			return false;
		}

		$files = scandir($dir);

		foreach($files as $i => $file){
			$path = realpath($dir . DIRECTORY_SEPARATOR . $file);
			if(is_file($path)) {
				if(!$exactly){
					if(strpos($file, $file_to_search) !== false){
						return ["file" => $file, "path" => $path];
					}
				}else{
					if($file == $file_to_search){
						return ["file" => $file, "path" => $path];
					}
				}
			}else if($file != "." and $file != "..") {
				$ret = $this -> deep_search_file($path, $file_to_search, $exactly);
				if($ret){
					return $ret;
				}
			}  
		} 

		return false;
	}

	public function scandirs($dir, &$results = array()) {
		if(!is_dir($dir)){
			return false;
		}
		
		$files = scandir($dir);

		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$results[] = $path;
			} else if ($value != "." && $value != "..") {
				$this -> scandirs($path, $results);
				$results[] = $path;
			}
		}

		return $results;
	}

	public function filesize_formatted($size){
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}

	public function cache_code_results($key, $func) {
		$cache_files = scandir(FCONF["cache_folder_path"]);

		if(!array_filter(
			$cache_files, 
			function($item) use($key) {
				return strpos($item, $key);
			})
		) {
			$results = $func();
			$timestamp = time();
			$cache_filepath = FCONF["cache_folder_path"] . "/{$timestamp}_{$key}.json";
			file_put_contents($cache_filepath, json_encode($results));
		} else {
			list($cache_filename) = array_values(array_filter(
				$cache_files, 
				function($item) use($key) {
					return strpos($item, $key);
				}
			));

			$cache_filepath = FCONF["cache_folder_path"] . "/{$cache_filename}";
			$results = json_decode(file_get_contents($cache_filepath));
		}

		if(!$this -> clear_cache_state_flag) {
			$this -> clear_cache_state_flag = true;
			array_map(function($cache_filename) {
				if(strpos($cache_filename, ".json")) {
					list($timestamp) = explode("_", $cache_filename);
					if(time() - intval($timestamp) > FCONF["cache_lifetime"]) {
						unlink(FCONF["cache_folder_path"] . "/{$cache_filename}");
					}
				}
			}, $cache_files);
		}

		return $results;
	}
}

