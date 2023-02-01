<?php

namespace Dashboard;

class Routes{
	/**
	 * Instance of Router module
	 * @var [type]
	 */
	protected $router;

	/**
	 * Controllers folder
	 * @var [type]
	 */
	protected $cf;

	/**
	 * Controllers namespace
	 * @var [type]
	 */
	protected $cn;

	/**
	 * @method __construct
	 * @param \Fury\Modules\Router\Router $router [description]
	 */
	public function __construct(\Fury\Modules\Router\Router $router){
		$this -> router = $router;
		$this -> cf = FCONF['controllers_folder'];
		$this -> cn = "\\" . FCONF['app_name'] . "\\" . FCONF['controllers_folder'];
	}

	public function routes_init(){
		$this -> uri_routes();
		$this -> get_routes();
		$this -> post_routes();
	}

	protected function uri_routes(){
		$this -> router -> uri('/dashboard', "{$this -> cn}\\Dashboard@board");
		$this -> router -> uri('/dashboard/status/$filter_status', "{$this -> cn}\\Dashboard@board");
		$this -> router -> uri('/dashboard/visibility/$project_name/$visibility_flag', "{$this -> cn}\\Dashboard@visibility_project");
	}

	protected function get_routes(){
		$this -> router -> get(['url'], "{$this -> cn}\\Dashboard@throw_img");
	}

	protected function post_routes(){
		$this -> router -> post(['setting_name', 'value'], "{$this -> cn}\\Dashboard@save_setting");
	}
}