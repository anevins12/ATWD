<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books extends CI_Controller {

	function __constuct() {
		
		parent::__construct();
	}

	public function index() {
		$courses = $this->courses();
		$data['courses'] = $courses['courses'];
		$this->load->view( 'books/books', $data);
	}

	public function course() {
		extract( $_GET );
		
		$this->load->model( 'Booksmodel' );
		$data[ 'error' ] = false;
		$data[ 'json' ] = false;
		$data[ 'requested' ] = 'course';
		$data[ 'format' ] = $format;

		if ( !is_string( $this->checkCourseID( $course_id ) ) ) { 
			
			$booksmodel = new Booksmodel();

			try { 
				$books = $booksmodel->getBooksByCourseId( $course_id );
			}
			catch ( Exception $e ) {
				$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='504' message='" . $e->getMessage() ."' /> \n</results>";
				$data[ 'service' ] = $error;
				$data[ 'error' ] = true;
			}

			if ( isset( $books ) ) {

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
					$data[ 'service' ] = $xml;

				}
			
				//if the formatted form option is JSON
				else if ( $data[ 'format' ] == 'JSON' ) {
					//construct the array that is to be converted to JSON
					$JSONarray = array( 'results' => array( 'course' => $course_id, 'books' => $books ) );

					//convert the JSON array to a JSON object
					$JSONobject = json_encode( $JSONarray );
					$data[ 'service' ] = $JSONobject;
				}

				else {
					$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='500' message='Service Error' /> \n</results>";
					$data[ 'service' ] = $error;
					$data[ 'error' ] = true;
				}
			}

		}

		//if the inputted course id has not matched with the XML 'database', return the error message from the exception
		else {
			$data[ 'service' ] = $this->checkCourseID( $course_id );
			$data[ 'error' ] = true;
		}

		if ( !$data[ 'error' ] ) {
			$data[ 'client' ] = $booksmodel->formatXML($data);
		}

		$courses =  $this->courses();
		$data[ 'courses' ] = $courses['courses'];

		$this->load->view( 'books/books', $data );
		
	}

	public function courses() {

		$this->load->model( 'coursesmodel' );
		$coursesmodel = new Coursesmodel();
		$data['courses'] = $coursesmodel->getAllCourses();
		
		return $data;
		
	}

	public function detail( $book_id = null ) {

		extract( $_GET );
		
		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();
		$courses = $this->courses();
		
		$data = $booksmodel->formatBookDetails();
		
		if ( $format == 'JSON' ) {
			$data[ 'service' ] = $booksmodel->formatXML($data);
		}
		else {
			$data = $booksmodel->formatXML($data);
		}
		
		$this->load->view( 'books/books', $data );
		
	}

	public function borrow() {

		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();
		
		extract( $_POST );
		$data[ 'format' ] = $format;
		
		
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

			$data[ 'service' ] = $xml;
		}
		//if the inputted book id has not matched with the any node in books.xml, return the error
		else {
			$data[ 'service' ] = $error;
			$data[ 'error' ] = true;
		}

		$courses = $this->courses();
		$data[ 'requested' ] = 'borrow';
		$data[ 'courses' ] = $courses[ 'courses' ];

		if ( $format == 'JSON' ) {
			$data[ 'service' ] = $booksmodel->formatXML( $data );
		}
		else {
			$data = $booksmodel->formatXML($data);
		}

		$this->load->view( 'books/books', $data );

	}

	public function suggestions() {

		$this->load->model( 'Suggestionsmodel' );
		$this->load->model( 'Booksmodel' );

		extract( $_GET );
		$data[ 'format' ] = $format;

		$suggestionsmodel = new Suggestionsmodel();
		$booksmodel = new Booksmodel();

		//handle exceptions if there are any
		try {
			$suggestions = $suggestionsmodel->getBookSuggestions( $book_id );
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?>\n<results>\n  <error id='502' message='" . $e->getMessage() ."' /> \n</results>";
		}
		
		if ( empty( $error ) ) {

			//sort the array by borrowedcount descending
			//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
			foreach ( $suggestions as $k => $row ) {
					$suggestionsSort[ $k ]  = $row[ 'total' ];
			}

			array_multisort( $suggestionsSort, SORT_DESC, $suggestions );
			if ( $format == 'XML' ) {

				$xml = "<?xml version='1.0' encoding='utf-8'?> \n<results> \n <suggestionsfor>$book_id</suggestionsfor>\n<suggestions>\n";

				foreach ( $suggestions as $suggestion ) {

					$xml .= "<isbn";
					foreach ( $suggestion as $k => $v ) {

						//dont use isbn as the XML node attribute, it's to be used further on in the node value
						if( $k != 'isbn' ) {
							$xml .= " $k='$v'";
						}

					}					

					$xml .= ">".$suggestion['isbn']."</isbn>\n";
				}

				$xml .= "</suggestions> \n</results>";

				$data[ 'service' ] = $xml;
			}


			else {
				$JSONarray = array( 'results' => array ( 'suggestionsfor' => $book_id, 'books' => array( 'suggestions' => $suggestions ) ) );
				$JSONobject = json_encode($JSONarray);
				$data[ 'service' ] = $JSONobject;
			}
		}
		//if the inputted book id has not matched with the any node in suggestions.xml, return the error
		else {
			$data[ 'xml' ] = $error;
			$data[ 'error' ] = true;
		}

		$data[ 'requested' ] = 'suggestions';
		$courses = $this->courses();
		$data['courses'] = $courses['courses'];

		if ( $format == 'JSON' ) {
			$data[ 'service' ] = $booksmodel->formatXML( $data );
		}
		else {
			$data = $booksmodel->formatXML($data);
		}

		$this->load->view( 'books/books', $data );

	}

	public function checkCourseID( $course_id ) {
		$error = "";

		$this->load->model( 'Coursesmodel' );
		$coursesmodel = new Coursesmodel();

		//handle exceptions if there are any
		try {
			 $coursesmodel->checkCourseId( $course_id );
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='501' message='" . $e->getMessage() ."' /> \n</results>";
		}

		if ( empty( $error ) ) {
			$courses = $coursesmodel->checkCourseId( $course_id );
			return $courses;
		}

		return $error;

	}

	public function suggestionsByISBN( $isbn ) {
		$this->load->model( 'booksmodel' );

		$booksmodel = new Booksmodel();
		$suggestions = $booksmodel->getBookDetailsByISBN( $isbn );
	}

	
}
/* End of file books.php */
/* Location: ./application/controllers/books.php */