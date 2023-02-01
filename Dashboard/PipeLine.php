<?php

namespace Dashboard;

class PipeLine{
	protected $data;
	protected $pipeline = [];

	public function __construct($pipeline = []){
		$this -> pipeline = $pipeline;
	}

	public function through_the_pipe($data){
		$this -> data = $data;
		$this -> processing();
		return $this -> get_data();
	}

	protected function processing(){
		foreach ($this -> pipeline as $i => $handler) {
			$this -> data = $handler($this -> data);
		}
	}

	public function pipe($data_handler){
		$this -> pipeline[] = $data_handler;
		return true;
	}

	public function get_data(){
		return $this -> data;
	}

	public function get_pipeline(){
		return $this -> pipeline;
	}
}