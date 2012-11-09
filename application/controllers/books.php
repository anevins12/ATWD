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
		
//		$this->Booksmodel->getBookDetailsReturnJSON("483");
//		$this->Suggestionsmodel->getBookSuggestionsReturnXML("51390");
//		$this->Suggestionsmodel->getBookSuggestionsReturnJSON("51390");
//		$this->Booksmodel->updateBorrowedData("51390", "CC140");

		$this->load->view('welcome_message');
	}

	public function course() {
		
		$this->load->model( 'Booksmodel' );
		
		//I know; using the extract twice now, the other time on the view
		extract( $_GET );
		
		$booksmodel = new Booksmodel();
		$books = $booksmodel->getBooksByCourseId( $course_id );

		
		//if the selected form format is XML
		if ( $format == 'XML' ) {

			$xml = "\n<results>\n <course>$course_id</course> \n <books> \n";

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
			$data['output'] = $xml;

		}
		
		//if the formatted form option is JSON
		else {
			//construct the array that is to be converted to JSON
			$JSONarray = array( 'results' => array( 'course' => $course_id, 'books' => $books ) );

			//sort the array by borrowedcount descending
			//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
			foreach ( $JSONarray[ 'results' ][ 'books' ] as $key => $row ) {
				$borrowedcountSort[$key]  = $row['borrowedcount'];
			}

			array_multisort($borrowedcountSort, SORT_DESC, $JSONarray[ 'results' ][ 'books' ]);

			//convert the JSON array to a JSON object
			$JSONobject = json_encode( $JSONarray );
			$data['output'] = $JSONobject;
		}

		$this->load->view( 'welcome_message', $data );

	}

	public function detail() {
		
		$this->load->model( 'Booksmodel' );

		//I know; using the extract twice now, the other time on the view
		extract( $_GET );

		$booksmodel = new Booksmodel();
		$books = $booksmodel->getBookDetails( $book_id );

		if ( $format == 'XML' ) {

			$xml = "<results> \n <book ";
			//should only be one book anyway
			foreach ( $books as $book ) {

				foreach ( $book as $k => $v ) {

					$xml .= " $k='$v'";

				}

			}

			$xml .= " /> \n</results>";
			$data[ 'output' ] = $xml;
			
		}
		
		else {

			$JSONarray = array( 'results' => array('book' => $books[0] ) );
			$data[ 'output' ] = $JSONarray;
		}

		$this->load->view( 'welcome_message', $data );
	}

	public function borrow() {

		extract( $_POST );
		
		$this->load->model( 'Booksmodel' );

		$booksmodel = new Booksmodel();
		$books = $booksmodel->updateBorrowedData( $book_id, $course_id);

		$xml = "<results> \n <book ";

		foreach ( $books as $book ) {
			
			foreach ( $book as $k => $v ) {

				$xml .= " $k='$v'";

			}
			$xml .= " /> \n</results>";

		}

		$data[ 'output' ] = $xml;
		$this->load->view( 'welcome_message', $data );

	}

	public function suggestions() {

		extract( $_GET );
		$this->load->model( 'Suggestionsmodel' );

		$suggestionsmodel = new Suggestionsmodel();
		$suggestions = $suggestionsmodel->getBookSuggestions( $suggestion_id );

		if ( $format == 'XML' ) {

			$xml = "<results> \n <suggestionsfor>$suggestion_id</suggestionsfor> \n  <books> \n   <suggestions>";

			foreach ( $suggestions as $suggestion ) {

				$xml .= "\n <isbn";
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

		$this->load->view( 'welcome_message', $data );

	}
	
}

/* End of file books.php */
/* Location: ./application/controllers/books.php */