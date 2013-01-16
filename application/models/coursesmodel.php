<?php

class Coursesmodel extends CI_Model {

	protected $file = "courses.xml";
	private $courses = array();

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
			$course->setIdAttribute( 'id', true );
		}

		//validate the document
		$file->validateOnParse = true;

		//get the course by course id, using the id
		$course = $file->getElementById( $course_id );
		
		return $course;

	}

	function getAllCourses() {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically

		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) .  $this->file );
		}
		else {
			$file->load( dirname(__FILE__) . '/../' . $this->config->item('xml_path') . $this->file );
		}

		$courses = $file->getElementsByTagName('course');

		foreach ( $courses as $course ) {
			$this->courses[] = array( 'name' => $course->nodeValue,
				                      'id' => $course->getAttribute('id'),
				                      'school' => $course->getAttribute('school') );
		}

		return $this->courses;

	}

	public function checkCourseId( $course_id ) {

		$course = array();
		
		$courses = $this->getAllCourses();

		$xml = "<courses>";

		foreach ( $courses as $course ) {

			$xml .= "\n <course id='" . $course['id'] . "' />";

		}
		
		$xml .= "\n </courses>";

		//using SimpleXML
		$xml = simplexml_load_string($xml);
		//try and match the course id from the View, to the id attribute of each course
		$course = $xml->xpath("//*[@id='$course_id']");

		if ( !empty ( $course ) ) {
			return true;
		}

		throw new Exception("Invalid Course ID $course_id.");

	}

}

?>
