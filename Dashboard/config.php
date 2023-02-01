<?php

return [
	"app_name" => "Dashboard",
	"debug" => true,
	"default_db_wrap" => false,
	// Not needs of sql database
	"db" => [
		"dblib" => "mysql",
		"host" => "127.0.0.1",
		"dbname" => "sample",
		"charset" => "utf8",
		"user" => "root",
		"password" => "pass"
	],
	"app_file" => "App.php",
	"templates_folder" => "Templates",
	"logs_enable" => true,
	"logs_folder" => "Dashboard/Logs",

	"controllers_folder" => "Controllers",

	// Array with folders of projects
	"projects_folders" => [
		"/var/www/html",
		"/usr/share/transmission"
	],
]; 
