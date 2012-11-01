<?php

class Coursesmodel extends CI_Model {

	protected $file = "courses.xml";

	function __construct() {
		parent::__construct();
		
	}

	function getCourseByCourseIdReturnXML( $course_id ) {
		
		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) .  $this->file );
		}
		else {
			$file->load( dirname(__FILE__) . '/../' . $this->config->item('xml_path') . $this->file );
		}
		
		//get all course nodes
		$courses = $file->getElementsByTagName('course');

		//set the course attribute of id to type of ID
		foreach ( $courses as $course ){
			$course->setIdAttribute( 'id', true);
		}

		//validate the document
		$file->validateOnParse = true;

		//get the course by course id, using the id
		$course = $file->getElementById( $course_id );
		
		return $course;

	}


}

?>
