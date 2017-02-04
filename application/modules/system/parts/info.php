<?php
class Info extends Part{

	protected $done;

	public function __construct($module){
		parent::__construct($module);

		$this->done = array();
	}

	public function index(){
		$i = 0;
		echo phpinfo();
	}
}