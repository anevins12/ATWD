<?php

class Coursesmodel extends CI_Model {

	protected $file = "courses.xml";

	function __construct() {
		parent::__construct();
		
	}

	function getCourseByCourseId( $course_id ) {
		$courses = new DOMDocument();


		//load the XML file into the DOM, loading statically
		$courses->load( dirname(__FILE__) . '/../' . $this->config->item('xml_path') . $this->file );

		$courses->getElementsByTagName('course');
		foreach ($courses as $course){
			$course->setIdAttribute( 'id', true);
		}


		//validate the document
		$courses->validateOnParse = true;

		var_dump($courses->getElementById($course_id));exit;

		$course = $courses->getElementsByTagName("course");
		




			return $course;

		}


}

?>
