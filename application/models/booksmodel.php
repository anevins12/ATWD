<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";

	function __construct() {
		parent::__construct();
	}

	function getBooksByCourseId ( $course_id, $sort = null ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );

		$saveBooksXML = "";

		foreach ( $books as $book ) {

			//get all course nodes
			$courses = $book->getElementsByTagName( 'course' );
			
			foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				if ( $course->nodeValue == $course_id ) {

					//save each book that has the matching book id 
					$saveBooksXML .= $file->saveXML($book) . "\n";
					
				}

			}
			
		}
		
		return $saveBooksXML;

	}

	function getBookDetails( $book_id ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );

		//set the book attribute of id to type of ID
		foreach ( $books as $book ){

			if ( $book->nodeName == 'item' ) {

				$book->setIdAttribute( 'id', true);

			}
			
		}

		//validate the document
		$file->validateOnParse = true;

		//get the book's detials through the book id
		$book_details = $file->getElementById( '3683' );

		//save that book's details
		$saveBookDetails = $file->saveXML($book_details);

		return $saveBookDetails;

	}

}

?>
