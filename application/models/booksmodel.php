<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";

	private $books = array();

	function __construct() {
		parent::__construct();
	}

	/* First attempt at using PHP and XSLT to bring in the correct XML.
	 * I hadn't known you could use PHP variables in XSL, until after I had wrote PHP to retrieve Books by Course Id.
	 * I then used XSLT to position and create the correct XML structure.
	 */
	function getBooksByCourseId ( $course_id ) {

		$flag = false;
		$file = new DOMDocument();
		
		//load the XML file into the DOM, loading statically
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) . $this->file );
		}
		else {
			$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );
		}
		//get all item nodes
		$books = $file->getElementsByTagName( 'item' );

		//start constructing returned xml
		//$xml = "\n<results>\n <course>$course_id</course> \n <books> \n";
		foreach ( $books as $book ) {

			//get all course nodes
			$courses = $book->getElementsByTagName( 'course' );
			
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

				//$xml .= "  <book id='$this->id' title='$this->title' isbn='$this->isbn' borrowedcount='$this->borrowedcount' /> \n";
			}

			$flag=false;
		}
		//$xml .= "\n </books>\n</results>";
		
		return $this->books;

	}

	function getBookDetails( $book_id ) {

		$file = new DOMDocument();

		//load XML file
		//if uwe server, get the full URI path
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']). '/application/' . $this->config->item( 'xml_path' ) . $this->file );
		}
		//otherwise use the normal path
		else {
			$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		}

		$books = $file->getElementsByTagName('item');
		
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

		return $this->books;

	}

	function updateBorrowedData( $item_id, $course_id ) {
		/* Not sure why I need $course_id */
		
		$file = new DOMDocument();
		
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) .  $this->file );
		}
		else {
			$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		}

		$books = $file->getElementsByTagName('item');

		foreach ( $books as $book ) {
			$book->setIdAttribute( 'id', true);
		}

		//validate the document
		$file->validateOnParse = true;

		//get the book by book id, using the id
		if ( $book = $file->getElementById( $item_id ) ) {

			$this->id = $book->getAttribute('id');
			$this->title = $book->getElementsByTagName('title')->item(0)->nodeValue;
			$this->isbn = $book->getElementsByTagName('isbn')->item(0)->nodeValue;
			$this->borrowedcount = $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue + 1;

		}

		//construct the xml
		$xml = "\n <results>\n <book id='$this->id' title='$this->title' isbn='$this->isbn' borrowedcount='$this->borrowedcount' /> \n </results>";

		return $xml;
		
	}

	

}

?>
