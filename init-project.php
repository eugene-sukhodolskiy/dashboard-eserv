<?php

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

function collect_data(){
	$data = [];
	$questions = [
		'Project name:' => 'name',
		'Version:' => 'ver',
		'Author [name<email>(site)]:' => 'author',
		'Tags:' => 'tags',
		'Project type:' => 'type',
		'Main language:' => 'main_lang'
	];

	foreach ($questions as $q => $prop_name) {
		$data[$prop_name] = readline($q . ' ');
	}

	$data['tags'] = array_map(function($item){
		return trim($item);
	}, explode(',', $data['tags']));

	if($data['ver'] == "\n" or $data['ver'] == ''){
		$data['ver'] = '1.0';
	}

	$data['authors'] = [ parse_author_string($data['author']) ];

	$data['status'] = "open";
	$data['project_color'] = null;
	$data['git_url'] = null;
	$data['release_url'] = null;
	$data['project_color'] = null;
	$data['favicon'] = null;
	$data['description'] = null;
	unset($data['author']);

	return $data;
}

function create_json($data){
	$json = json_encode($data, JSON_PRETTY_PRINT);
	$dir = readline("Path to root dir of project: ");
	if($dir == "\n" or $dir == ""){
		$dir = getcwd();
	}

	$dir .= '/dashboard.json';
	return file_put_contents($dir, $json);
}

$data = collect_data();
create_json($data);