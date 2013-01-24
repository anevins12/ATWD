<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books extends CI_Controller {

	function __constuct() {

		parent::__construct();
	}

	public function index() {
		$courses = $this->courses();
		$data['courses'] = $courses['courses'];
		$this->load->view('welcome_message', $data);
		$this->load->library('javascript');
	}

	public function book( $id ) {

		
	}

}


?>