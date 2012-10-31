<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";

	function __construct() {
		parent::__construct();
	}

	/* First attempt at using PHP and XSLT to bring in the correct XML.
	 * I hadn't known you could use PHP variables in XSL, until after I had wrote PHP to retrieve Books by Course Id.
	 * I then used XSLT to position and create the correct XML structure.
	 */
	function getBooksByCourseIdReturnXML ( $course_id ) {

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

	function getBooksByCourseIdReturnJSON ( $course_id ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );
		$booksArray = array();

		foreach ( $books as $book ) {

			//get all course nodes
			$courses = $book->getElementsByTagName( 'course' );

			foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				if ( $course->nodeValue == $course_id ) {
					$flag = true;
				}
				else {
					$flag = false;
				}
				
			}

			if ( $flag ) {

				//variable assigning implemeneted here to avoid duplicating fields if more than one course (above foreach)
			
				$id = $book->getAttribute( 'id' );
				$title = $book->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$isbn = $book->getElementsByTagName( 'isbn' )->item( 0 )->nodeValue;
				$borrowedcount = $book->getElementsByTagName( 'borrowedcount' )->item( 0 )->nodeValue;

				$booksArray[] = array( 'book' => array( 'id' => $id, 'title' => $title, 'isbn' => $isbn, 'borrowedcount' => $borrowedcount ) );
	
				unset( $flag );

			}

		}

		//construct the array that is to be converted to JSON
		$JSONarray = array( 'results' => array( 'course' => $course_id, 'books' => $booksArray ) );

		//sort the array by borrowedcount descending
		//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
		foreach ($JSONarray[0]['books'] as $key => $row) {
			$borrowedcountSort[$key]  = $row['book']['borrowedcount'];
		}
		array_multisort($borrowedcountSort, SORT_DESC, $JSONarray[0]['books']);

		//convert the JSON array to a JSON object
		$JSONobject = json_encode($JSONarray);

		return $JSONobject;

	}

	function getBookDetailsReturnXML( $book_id ) {

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
	
	function getBookDetailsReturnJSON ( $book_id ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );

		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );
		$bookArray = array();

		//set the id attribute as a type of ID, for future getElementsById methods
		foreach ( $books as $book ) {

			$book->setIdAttribute( 'id', true);

		}

		//validate the document
        $file->validateOnParse = true;

		//now get the book by its id value
		$book = $file->getElementById($book_id);

		if ( $book ) {

			$id = $book->getAttribute( 'id' );
			$title = $book->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
			$isbn = $book->getElementsByTagName( 'isbn' )->item( 0 )->nodeValue;
			$borrowedcount = $book->getElementsByTagName( 'borrowedcount' )->item( 0 )->nodeValue;

			$bookArray = array( 'book' => array( 'id' => $id, 'title' => $title, 'isbn' => $isbn, 'borrowedcount' => $borrowedcount ) );
		}


		//construct the array that is to be converted to JSON
		$JSONarray = array( 'results' => $bookArray );

		//convert the JSON array to a JSON object
		$JSONobject = json_encode($JSONarray);

		return $JSONobject;

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

		return $newXML;
		
	}

	

}

?>
