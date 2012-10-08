<?php

class Coursesmodel extends CI_Model {

	protected $file = "courses.xml";

	function __construct() {
		parent::__construct();
	}

	function getXMLFile() {
		return simplexml_load_file( $_SERVER['DOCUMENT_ROOT'] . $this->config->item('xml_path') . $this->file );
	}

	function getAllCourses() {

		$xml = $this->getXMLFile();
		$courses = $xml->courses;

		return $courses;

	}

}

?>
