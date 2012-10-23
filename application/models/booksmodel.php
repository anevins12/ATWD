<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";

	function __construct() {
		parent::__construct();
	}

	function getBooksByCourseId( $course_id, $sort = null ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );

		foreach ( $books as $book ){

			//get all course nodes
			$courses = $book->getElementsByTagName( 'course' );
			
			foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				if ( $course->nodeValue == $course_id ) {

					//save each book that has the matching book id 
					$file->saveXML($book);

				}

			}
			
		}
		
		return $file;

	}

}

?>
