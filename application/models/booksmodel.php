<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";
	private $books = array();

	function __construct() {
		parent::__construct();
		$this->load->library('applicationpath');
	}

	function getBooksByCourseId ( $course_id ) {

		$flag = false;
		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );

		//check if directory path exists
		if ( !is_dir( $xmlPath ) ) {
			show_error( 'Directory Path to XML file does not exist' );
			log_message( 'error', 'Directory Path to XML file does not exist' );
		}

		//check if file has an XML extension
		if ( !pathinfo( $xmlPath . $this->file, PATHINFO_EXTENSION ) ) {
			show_error( 'Input file must be an XML file' );
			log_message( 'error', 'Input file must be an XML file' );
		}
		
		//load the XML file into the DOM, loading statically
		$file->load($xmlPath . $this->file);

		//check if file has loaded
		if ( !$file ) {
			show_error('There was no XML file loaded');
			log_message('error', 'No XML file was loaded');
		}

		if ( $file->getElementsByTagName( 'item' ) ) {
			//get all item nodes
			$books = $file->getElementsByTagName( 'item' );
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}

		//start constructing returned xml
		foreach ( $books as $book ) {

			//if there is a node named 'course'
			if ( $book->getElementsByTagName( 'course' ) )  {
				//get all course nodes
				$courses = $book->getElementsByTagName( 'course' );
			}
			else {
				show_error( "The XML file contains no nodes named 'course'" );
				log_message( 'error', "XML file has no 'course' nodes" );
			}

			//no need to check whether $courses exists, as if it isn't, the error message will show
			foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				if ( $course->nodeValue == $course_id ) {
					$flag = true;
				}

			}

			//get out of the course loop and just use flag to identify whether matched course id
			if ( $flag ) {

				//populate array with node name as key, and node value as value
				$this->books[] = array( $book->getAttributeNode('id')->nodeName => $book->getAttribute('id'),
									    $book->getElementsByTagName('title')->item(0)->nodeName => $book->getElementsByTagName('title')->item(0)->nodeValue,
									    $book->getElementsByTagName('isbn')->item(0)->nodeName =>	$book->getElementsByTagName('isbn')->item(0)->nodeValue,
									    $book->getElementsByTagName('borrowedcount')->item(0)->nodeName => $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue
									   );

			}

			$flag=false;
		}

		return $this->books;

	}

	function getBookDetails( $book_id ) {

		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );

		//check if directory path exists
		if ( !is_dir( $xmlPath ) ) {
			show_error( 'Directory Path to XML file does not exist' );
			log_message( 'error', 'Directory Path to XML file does not exist' );
		}

		//check if file has an XML extension
		if ( !pathinfo( $xmlPath . $this->file, PATHINFO_EXTENSION ) ) {
			show_error( 'Input file must be an XML file' );
			log_message( 'error', 'Input file must be an XML file' );
		}

		//load the XML file into the DOM, loading statically
		$file->load($xmlPath . $this->file);

		//check if file has loaded
		if ( !$file ) {
			show_error('There was no XML file loaded');
			log_message('error', 'No XML file was loaded');
		}

		//if there is a node named 'item'
		if ( $file->getElementsByTagName( 'item' ) ) {
			//get all item nodes
			$books = $file->getElementsByTagName( 'item' );
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}
		
		foreach ( $books as $book ) {
			$book->setIdAttribute( 'id', true);
		}

		//validate the document
		$file->validateOnParse = true;

		//get the book by book id, using the id
		if ( $book = $file->getElementById( $book_id ) ) {

			//populate array with node name as key, and node value as value
			$this->books[] = array( $book->getAttributeNode('id')->nodeName => $book->getAttribute('id'),
									$book->getElementsByTagName('title')->item(0)->nodeName => $book->getElementsByTagName('title')->item(0)->nodeValue,
									$book->getElementsByTagName('isbn')->item(0)->nodeName =>	$book->getElementsByTagName('isbn')->item(0)->nodeValue,
									$book->getElementsByTagName('borrowedcount')->item(0)->nodeName => $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue
								   );
			
		}
		else {
			throw new Exception( "Invalid Book ID $book_id" );
		}
		return $this->books;

	}

	function updateBorrowedData( $item_id, $course_id ) {
		/* Not sure why I need $course_id */

		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );

		//check if directory path exists
		if ( !is_dir( $xmlPath ) ) {
			show_error( 'Directory Path to XML file does not exist' );
			log_message( 'error', 'Directory Path to XML file does not exist' );
		}

		//check if file has an XML extension
		if ( !pathinfo( $xmlPath . $this->file, PATHINFO_EXTENSION ) ) {
			show_error( 'Input file must be an XML file' );
			log_message( 'error', 'Input file must be an XML file' );
		}

		//load the XML file into the DOM, loading statically
		$file->load($xmlPath . $this->file);

		//check if file has loaded
		if ( !$file ) {
			show_error('There was no XML file loaded');
			log_message('error', 'No XML file was loaded');
		}

		//if there is a node named 'item'
		if ( $file->getElementsByTagName('item') ) {
			$books = $file->getElementsByTagName('item');
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}

		foreach ( $books as $book ) {
			$book->setIdAttribute( 'id', true);
		}

		//validate the document
		$file->validateOnParse = true;

		//get the book by book id, using the id
		if ( $book = $file->getElementById( $item_id ) ) {

		//using SimpleXML to update the XML file of its borrowed count
		$xml = simplexml_load_file($xmlPath . $this->file);
		$simplexml_book = $xml->xpath("//*[@id='$item_id']");
		$simplexml_book[0]->borrowedcount++;
		$xml->asXml($xmlPath . $this->file);

		//I'm still using the DOMDocument for setting the array, because I found getting the @attribute value really difficult with SimpleXML
		$this->books[] = array( $book->getAttributeNode('id')->nodeName => $book->getAttribute('id'),
									$book->getElementsByTagName('title')->item(0)->nodeName => $book->getElementsByTagName('title')->item(0)->nodeValue,
									$book->getElementsByTagName('isbn')->item(0)->nodeName =>	$book->getElementsByTagName('isbn')->item(0)->nodeValue,
									$book->getElementsByTagName('borrowedcount')->item(0)->nodeName => $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue + 1
								   );

		}
		//item id not found in books.xml
		else {
			throw new Exception( "Invalid Book ID $item_id" );
		}
		
		return $this->books;
		
	}

	

}

?>
