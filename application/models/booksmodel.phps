<?php

class Booksmodel extends CI_Model {

	protected $file = "books.xml";
	private $books = array();

	function __construct() {
		parent::__construct();
		
		//applicationpath is a simple library I made that returns the path to include XML and XSLT files.
		$this->load->library('applicationpath');
	}
	
/**
 * Gets all books that match the course id in the parameter
 *
 * @access	public
 * @param	string
 * @return	array
 */    
	function getBooksByCourseId ( $course_id ) {
		
		#$flag = false;
		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );
		$simplexml = simplexml_load_file($xmlPath . $this->file);
		
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
		
			//get all books by course id
			$books = $simplexml->xpath( "items/item[courses/course/text()='$course_id']" );			
			#Attempted DOMDocument
			//$books = $file->getElementsByTagName( 'item' );
			
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}
		
		
		//start constructing returned xml
		foreach ( $books as $book ) {
			
			#Attempted DOMDocument
			//if there is a node named 'course'
			//if ( $book->getElementsByTagName( 'course' ) )  {
				//get all course nodes
			//	$courses = $book->getElementsByTagName( 'course' );
			//}
			//else {
			//	show_error( "The XML file contains no nodes named 'course'" );
			//	log_message( 'error', "XML file has no 'course' nodes" );
			//}

			//no need to check whether $courses exists, as if it isn't, the error message above will show
			//foreach ( $courses as $course ) {

				//check whether the course id of the node matches the course id of user input
				//if ( $course->nodeValue == $course_id ) {
					//get out of the course loop and just use flag to identify whether matched course id
				//	$flag = true;
			//	}

			//}
			
			//escape syntax-error-causing characters
			$title = addSlashes((string)$book[0]->title);
			$title = htmlentities((string)$title, ENT_QUOTES, "ISO-8859-5");
			
			//populate array 
			$this->books[] = array( 'id' => (string) $book[0]->attributes()->id,
									'title' => (string) $title,
									'isbn' => (string) $book[0]->isbn,
									'borrowedcount' => (string)$book[0]->borrowedcount
									);

		}
		

		if ( !$this->books ) {
			throw new Exception( "No books found" );
		} 

		return $this->books;

	}
/**
 * Get a book's details for the book with the matched book id paramter
 *
 * @access	public
 * @param	string
 * @return	boolean
 */    
	function getBookDetails( $book_id ) {

		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );
		$simplexml = simplexml_load_file($xmlPath . $this->file);
		
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
			//get all book by book id
			$book = $simplexml->xpath( "//*[@id='$book_id']" );
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}
		
		if ( count( $book ) > 0 ) {
			
			//Get the title for HTML entity parsing
			$title = $book[0]->title;
			//escape syntax-error-causing characters
			$book[0]->title = htmlentities( $title, ENT_QUOTES, "ISO-8859-5");
		
			$this->books[] = array ( 'id' => (string) $book[0]->attributes()->id,
									'title' => (string) $book[0]->title,
									'isbn' => (string) $book[0]->isbn,
									'borrowedcount' => (string) $book[0]->borrowedcount
									);
			
		}
		
		else {
			throw new Exception( "Invalid Book ID $book_id" );
		} 
		
		return $this->books;
		
		
		#Attempted using DOMDocument
		//A lot of the processing time is created by this setting of the attribute a type of ID
		//foreach ( $books as $book ) { 
		//	$book->setIdAttribute( 'id', true);
		
			//validate the document
		//	$file->validateOnParse = true;

		//	$title = $book->getElementsByTagName('title')->item(0)->nodeValue;
			//escape syntax-error-causing characters
		//	$book->getElementsByTagName('title')->item(0)->nodeValue = htmlentities( $title, ENT_QUOTES, "ISO-8859-5");
		///}
                
		
		//if ( $book = $file->getElementById( $book_id ) ) {
	    
 				//populate array with node name as key, and node value as value
		//		$this->books[] = array( $book->getAttributeNode('id')->nodeName => $book->getAttribute('id'),
		//								$book->getElementsByTagName('title')->item(0)->nodeName => $book->getElementsByTagName('title')->item(0)->nodeValue,
		//								$book->getElementsByTagName('isbn')->item(0)->nodeName =>	$book->getElementsByTagName('isbn')->item(0)->nodeValue,
		//								$book->getElementsByTagName('borrowedcount')->item(0)->nodeName => $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue
		//							);

		//}
		//else {
		//		throw new Exception( "Invalid Book ID $book_id" );
		//} 
                
		//return $this->books;

	}
	
/**
 * Increments one book's borrowed count by a value of 1
 *
 * @access	public
 * @param	string, string
 * @return	array
 */    
	function updateBorrowedData( $item_id, $course_id ) { 

		$file = new DOMDocument();
		$xmlPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xml_path' );
		$simplexml = simplexml_load_file($xmlPath . $this->file);

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
			#Attempted using DOMDocument
			// $books = $file->getElementsByTagName('item');
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}
		
		//if there is a node named 'item'
		if ( $file->getElementsByTagName( 'item' ) ) {
			//get all book by book id
			$book = $simplexml->xpath( "//*[@id='$item_id']" );
		}
		else {
			show_error( "The XML file contains no nodes named 'item'" );
			log_message( 'error', "XML file has no 'item' nodes" );
		}
		
		if ( count( $book ) > 0 ) {
			
			//escape syntax-error-causing characters
			//$book[0]->title = htmlentities( $book[0]->title, ENT_QUOTES, "ISO-8859-5");
			
			$this->books[] = array ( 'id' => $book[0]->attributes()->id,
									'title' => htmlentities( $book[0]->title, ENT_QUOTES, "ISO-8859-5"),
									'isbn' => $book[0]->isbn,
									'borrowedcount' => $book[0]->borrowedcount
									);
									
			$book[ 0 ]->borrowedcount = $book[ 0 ]->borrowedcount + 1;
			$simplexml->asXml($xmlPath . $this->file);
		}
		
		else {
			throw new Exception( "Invalid Book ID $item_id" );
		} 
		
		return $this->books;
		
			
		#Attempted using DOMDocument
		//foreach ( $books as $book ) {
		//	$book->setIdAttribute( 'id', true);
		//}
		
		//validate the document
		//$file->validateOnParse = true;

		//$title = $book->getElementsByTagName('title')->item(0)->nodeValue;
		//escape syntax-error-causing characters
		//$title = htmlentities( $title, ENT_QUOTES, "ISO-8859-5");

		//get the book by book id, using the id
		//if ( $book = $file->getElementById( $item_id ) ) {

			//using SimpleXML to update the XML file of its borrowed count
		//	$xml = simplexml_load_file($xmlPath . $this->file);
		//	$simplexml_book = $xml->xpath( "//*[@id='$item_id']" );
			
		//	$simplexml_book[ 0 ]->borrowedcount = $simplexml_book[ 0 ]->borrowedcount + 1;
			
		//	$xml->asXml($xmlPath . $this->file);

			//I'm still using the DOMDocument for setting the array, because I found getting the @attribute value really difficult with SimpleXML
		//	$this->books[] = array( $book->getAttributeNode( 'id' )->nodeName => $book->getAttribute('id'),
        //                                        $book->getElementsByTagName( 'title' )->item( 0 )->nodeName => $book->getElementsByTagName('title')->item(0)->nodeValue,
        //                                        $book->getElementsByTagName( 'isbn' )->item( 0 )->nodeName =>	$book->getElementsByTagName('isbn')->item(0)->nodeValue,
        //                                        $book->getElementsByTagName( 'borrowedcount' )->item(0)->nodeName => $book->getElementsByTagName('borrowedcount')->item(0)->nodeValue + 1
        //                                       );

		//}
			
		//item id not found in books.xml
		//else {
		//	throw new Exception( "Invalid Book ID $item_id" );
		//}
		
		return $this->books;
		
	}
	
/**
 * Called by the Books controller detail function, it constructs XML that holds the book's details
 *
 * @access	public
 * @return	array if requested by client |  string if requested by service | json if requested by format of json
 */    
 
	public function formatBookDetails() {

		$error = "";
		extract( $_GET );
		$data[ 'format' ] = strtolower($format);

		try { 
			$books = $this->getBookDetails( $book_id );
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='502' message='" . $e->getMessage() ."' /> \n</results>";
		}

		if ( empty( $error ) ) { 
		$format = strtoupper($format);
                        
			if ( $format == 'XML' || $format == 'JSON') {
                            
				if ( $format == 'XML' ) {

					$xml = "<?xml version='1.0' encoding='utf-8'?> \n<results> \n <book ";
					//should only be one book anyway
					foreach ( $books as $book ) {

						foreach ( $book as $k => $v ) {

							$xml .= " $k='$v'";

						}

					}

					$xml .= " /> \n</results>";
					$data[ 'service' ] = $xml;
                                
				}
                            
				else if ( $format == 'JSON' ) {
					
					$JSONarray = array( 'results' => array('book' => $books[ 0 ] ) );
					$data[ 'service' ] = json_encode( $JSONarray );
                                
				}

			}

			//if format is not XML or JSON
			else {
				
				$error =  "<?xml version='1.0' encoding='UTF-8'?> \n<results>\n  <error id='500' message='Service Error' /> \n</results>";
				$data[ 'service' ] = $error;
				$data[ 'error' ] = true;

			}
		}
                
		//if the inputted book id has not matched with the any node in books.xml, return the error
		else {
			$data[ 'service' ] = $error;
			$data[ 'error' ] = true;
		}

		$data[ 'requested' ] = 'detail';
		return $data;
		
	}
	
/** 
 * Takes an array from its parameter and checks whether the format is XML. If so, it loads in XSLT to style the XML and returns the transformed XML 
 * 
 * @param   array
 * @access	public
 * @return	json if requested format is json | array 
 */   
	public function formatXML( $data ) { 
		$data['format'] = strtoupper($data['format']);
        extract($_GET); 
		if ( $data[ 'format' ]  == 'JSON' || !isset( $submit ) && $data[ 'format' ] != 'XML' ) {  
			return $data = $data[ 'service' ]; 
		}

		if ( isset( $data[ 'requested' ] ) ){ 
			$requested = $data[ 'requested' ];
		}
		
		$xslt_filename = 'format' . ucfirst( $requested );

		if ( isset( $data[ 'error' ] ) && $data[ 'error' ] ){ 
			$xslt_filename = 'formatError'; 
		}

		//exception if $request format is not Course, Detail, Borrow or Suggestion
		$this->load->library( 'applicationPath' );
		$xslPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xsl_path' );

		# FROM: http://php.net/manual/en/book.xsl.php
		# LOAD XML FILE
		$xml = new DOMDocument();
 
		#Stop silly UTF errors, "Input is not proper UTF-8" that only appear on UWE servers 
		#http://www.mybelovedphp.com/2009/07/03/fix-broken-utf8-encoded-rss-feeds-in-php/                
		$data[ 'service' ] = iconv( "UTF-8", "UTF-8//IGNORE", $data[ 'service' ] );    
		
		if ( !isset( $data[ 'error' ] ) || !$data[ 'error'] ) {
			$xml->loadXML( $data[ 'service' ] );
		}

		# START XSLT
		$xslt = new XSLTProcessor();
		$xsl = new DOMDocument();
		$xsl->load( $xslPath . '/' . $xslt_filename . '.xsl' );

		$xslt->importStylesheet( $xsl );
		$data[ 'client' ] = $xslt->transformToXML( $xml );

		return $data;
	
	}
	
 /** 
 * Constructs XML from the data of the updateBorrowData function that returns an array. This XML contains the book's details.
 * 
 * @param   string
 * @access	public
 * @return	array
 */         
	public function borrow( $course_id = null) {
		
		extract( $_POST ); 
		$data[ 'format' ] = 'XML';
		
		//if there is no book_id in the post variable, set it as an empty string so it doesn't turn into a notice
		if ( !isset( $book_id ) ) {
			$book_id = '';
		}
		
		try {
			$books = $this->updateBorrowedData( $book_id, $course_id );
		}
		
		catch ( Exception $e ) {
			$error = "<?xml version='1.0' encoding='utf-8'?>\n<results>\n  <error id='502' message='" . $e->getMessage() ."' /> \n</results>";
		}
		
		$xml = "<?xml version='1.0' encoding='utf-8'?><results> \n <book ";

		if ( empty( $error ) ) {
			foreach ( $books as $book ) {

				foreach ( $book as $k => $v ) {

					$xml .= " $k='$v'";

				}
				$xml .= " /> \n</results>";

			}

			$data[ 'service' ] = $xml;
		}
		//if the inputted book id has not matched with the any node in books.xml, return the error
		else {
			$data[ 'service' ] = $error;
			$data[ 'error' ] = true;
		}

		$data[ 'requested' ] = 'borrow';

		//client here serves both service and client purposes - the check is done later on in the books model
		$data[ 'client' ] = $this->formatXML( $data );
		
		return $data;
                
	}

}

?>
