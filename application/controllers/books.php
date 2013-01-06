<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books extends CI_Controller {

	function __constuct() {
		parent::__construct();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){

		$this->load->view('welcome_message');
		
	}

	public function course() {
		
		$this->load->model( 'Booksmodel' );
		$data[ 'error' ] = false;
		
		//I know; using the extract twice now, the other time on the view
		extract( $_GET );

		if ( !is_string( $this->checkCourseID() ) ) {
			
			$booksmodel = new Booksmodel();
			$books = $booksmodel->getBooksByCourseId( $course_id );

			//sort the array by borrowedcount descending
			//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
			foreach ( $books as $k => $row ) {
					$borrowedcountSort[ $k ]  = $row[ 'borrowedcount' ];
			}

			array_multisort( $borrowedcountSort, SORT_DESC, $books );

			//if the selected form format is XML
			if ( $format == 'XML' ) {

				$xml = "<?xml version='1.0' encoding='utf-8'?>\n<results>\n <course>$course_id</course> \n <books> \n";

				foreach ( $books as $book ) {

					//construct the XML format for each book
					$xml .="  <book";

					//use the array's keys and values
					foreach ( $book as $k => $v ){

						//as attributes and values
						$xml .= " $k='$v'";

					}

					$xml .="/> \n";

				}

				$xml .= "\n </books>\n</results>";
				$data[ 'xml' ] = $xml;

			}
			
			//if the formatted form option is JSON
			else {
				//construct the array that is to be converted to JSON
				$JSONarray = array( 'results' => array( 'course' => $course_id, 'books' => $books ) );

				//convert the JSON array to a JSON object
				$JSONobject = json_encode( $JSONarray );
				$data[ 'output' ] = $JSONobject;
			}
		}
		//if the inputted course id has not matched with the XML 'database', return the error message from the exception
		else {
			$data[ 'xml' ] = $this->checkCourseID();
			$data[ 'error' ] = true;
		}
		
		$data[ 'requested' ] = 'course';

		$this->format( $data );
	}

	public function detail() {
		
		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();

		$error = "";
		extract( $_GET );

		try {
			 $books = $booksmodel->getBookDetails( $book_id );
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='502' message='" . $e->getMessage() ."' /> \n</results>";
		}

		if ( empty( $error ) ) {

			if ( $format == 'XML' ) {

				$xml = "<?xml version='1.0' encoding='utf-8'?> \n<results> \n <book ";
				//should only be one book anyway
				foreach ( $books as $book ) {

					foreach ( $book as $k => $v ) {

						$xml .= " $k='$v'";

					}

				}

				$xml .= " /> \n</results>";
				$data[ 'xml' ] = $xml;

			}

			else {

				$JSONarray = array( 'results' => array('book' => $books[0] ) );
				$data[ 'output' ] = $JSONarray;
			}
		}
		//if the inputted book id has not matched with the any node in books.xml, return the error
		else {
			$data[ 'xml' ] = $error;
			$data[ 'error' ] = true;
		}

		$data[ 'requested' ] = 'detail';
		$this->format( $data );
	}

	public function borrow() {

		extract( $_POST );
		
		$this->load->model( 'Booksmodel' );

		$booksmodel = new Booksmodel();
		
		try {
			$books = $booksmodel->updateBorrowedData( $book_id, $course_id );
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

			$data[ 'xml' ] = $xml;
		}
		//if the inputted book id has not matched with the any node in books.xml, return the error
		else {
			$data[ 'xml' ] = $error;
			$data[ 'error' ] = true;
		}
		
		$data[ 'requested' ] = 'borrow';
		$this->format( $data );

	}

	public function suggestions() {

		extract( $_GET );
		$this->load->model( 'Suggestionsmodel' );

		$suggestionsmodel = new Suggestionsmodel();

		//handle exceptions if there are any
		try {
			$suggestions = $suggestionsmodel->getBookSuggestions( $suggestion_id );
		}
		catch ( Exception $e ) {
			$error =  "\n<results>\n  <error id='502' message='" . $e->getMessage() ."' /> \n</results>";
		}
		
		if ( empty( $error ) ) {
			if ( $format == 'XML' ) {

				$xml = "<results> \n <suggestionsfor>$suggestion_id</suggestionsfor> \n  <books> \n   <suggestions>";

				foreach ( $suggestions as $suggestion ) {

					$xml .= "\n    <isbn";
					foreach ( $suggestion as $k => $v ) {

						//dont use isbn as the XML node attribute, it's to be used further on in the node value
						if( $k != 'isbn' ) {
							$xml .= " $k='$v'";
						}

					}

					$xml .= ">".$suggestion['isbn']."</isbn>";
				}

				$xml .= "</suggestions> \n</results>";

				$data[ 'output' ] = $xml;
			}


			else {
				$JSONarray = array( 'results' => array ( 'suggestionsfor' => $suggestion_id, 'books' => array( 'suggestions' => $suggestions[0] ) ) );
				$JSONobject = json_encode($JSONarray);
				$data[ 'output' ] = $JSONobject;
			}
		}
		//if the inputted book id has not matched with the any node in suggestions.xml, return the error
		else {
			$data[ 'output' ] = $error;
		}

		$this->load->view( 'welcome_message', $data );

	}

	public function checkCourseID() {
		$error = "";

		$this->load->model( 'Coursesmodel' );
		$coursesmodel = new Coursesmodel();

		//handle exceptions if there are any
		try {
			 $coursesmodel->checkCourseId();
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='501' message='" . $e->getMessage() ."' /> \n</results>";
		}

		if ( empty( $error ) ) {
			$courses = $coursesmodel->checkCourseId();
			return $courses;
		}

		return $error;

	}

	public function format( $data ) {
		
		if ( isset( $data[ 'requested' ] ) ){
			$requested = $data[ 'requested' ];
		}

		$xslt_filename = 'format' . ucfirst( $requested );

		if ( isset( $data[ 'error' ] ) && $data[ 'error' ] ){
			$xslt_filename = 'formatError';
		}
		//exception if $request format is not Course, Detail, Borrow or Suggestion
		
		$xslPath = $this->applicationpath->getApplicationPath() . $this->config->item( 'xsl_path' );

		# FROM: http://php.net/manual/en/book.xsl.php
		# LOAD XML FILE
		$xml = new DOMDocument();
		$xml->loadXML( $data[ 'xml' ] );
		
		# START XSLT
		$xslt = new XSLTProcessor();
		$xsl = new DOMDocument();
		$xsl->load( $xslPath . '/' . $xslt_filename . '.xsl' );

		$xslt->importStylesheet( $xsl );
		$data[ 'output' ] = $xslt->transformToXML( $xml );

		$this->load->view( 'welcome_message', $data );
	}

	
}
/* End of file books.php */
/* Location: ./application/controllers/books.php */