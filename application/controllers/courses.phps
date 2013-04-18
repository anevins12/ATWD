<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses extends CI_Controller {
    
        function __constuct() {
		parent::__construct();
	}
        
        public function index() {
            $this->getAllCourses();
        }
    
    	public function getAllCourses() { 
			$this->load->model( 'coursesmodel' );
			$coursesmodel = new Coursesmodel();
			$data['courses'] = json_encode( $coursesmodel->getAllCourses() );
			
			$this->load->view( 'courses/index', $data );
        }
        
}