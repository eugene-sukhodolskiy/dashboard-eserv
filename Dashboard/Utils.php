<?php

namespace Dashboard;

class Utils{
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
}

