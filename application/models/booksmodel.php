<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";

	function __construct() {
		parent::__construct();
	}

	function getBooksByCourseId ( $course_id ) {

		$stylesheet = 'getBooksByCourseId.xsl';
		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );

		$saveBooksXML = "";
		$newFile = new DOMDocument();
		$newFile->loadXML( "<results></results>" );
		$newFile->saveXML();

		$xsl = new DOMDocument();
		$xsl->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . '/xsl/' . $stylesheet );

		$proc = new XSLTProcessor();
		$proc->importStylesheet( $xsl );
		$proc->setParameter( '', 'course', $course_id );

		foreach ( $books as $book ) {

			//get all course nodes
			$courses = $book->getElementsByTagName( 'course' );
			
			foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				if ( $course->nodeValue == $course_id ) {

					$node = $newFile->importNode( $book, true );
					$newFile->documentElement->appendChild( $node );
					
				}

			}
			
		}

		//save each book that has the matching book id
		$newFile->saveXML();
		$newXML = $proc->transformToXml( $newFile );

		return $newXML;

	}

	/* NOTE:
	 * Does not work with IDs of 3 values
	 *
	 */

	function getBookDetails( $book_id ) {

		$stylesheet = 'getBookDetails.xsl';
		$file = new DOMDocument();

		$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		$file->saveXML();

		$xsl = new DOMDocument();
		$xsl->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . '/xsl/' . $stylesheet );

		$proc = new XSLTProcessor();
		$proc->importStylesheet( $xsl );
		$proc->setParameter( '', 'book_id', $book_id );

		//save the matched book
		$file->saveXML();
		$newXML = $proc->transformToXml( $file );

		return $newXML;

	}

	function updateBorrowedData( $item_id, $course_id ) {
		/* Not sure why I need $course_id */
		
		$stylesheet = 'updateBorrowedData.xsl';
		$file = new DOMDocument();

		$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		$file->saveXML();

		$xsl = new DOMDocument();
		$xsl->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . '/xsl/' . $stylesheet );

		$proc = new XSLTProcessor();
		$proc->importStylesheet( $xsl );
		$proc->setParameter( '', 'book_id', $item_id );

		//save the matched book
		$file->saveXML();
		$newXML = $proc->transformToXml( $file );

		var_dump($newXML);exit;
		return $newXML;
		
	}

	

}

?>
