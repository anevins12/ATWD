<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books extends CI_Controller {

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
		$this->load->model('Booksmodel');
		$this->load->model('Suggestionsmodel');
		
		$this->Booksmodel->getBookDetailsReturnXML("483");
		$this->Booksmodel->getBookDetailsReturnJSON("483");
		$this->Suggestionsmodel->getBookSuggestionsReturnXML("51390");
		$this->Suggestionsmodel->getBookSuggestionsReturnJSON("51390");
		$this->Booksmodel->updateBorrowedData("51390", "CC140");

		$this->load->view('welcome_message');
	}

	public function getBooksByCourseId() {
		
		$this->load->model('Booksmodel');
		extract($_GET);
		
		$booksmodel = new Booksmodel();
		$books = $booksmodel->getBooksByCourseIdReturnXML( $course_id );

		//I know; using the extract twice now, the other time on the view
		

		//if the selected form format is XML
		if ( $format == 'XML' ) {

			$xml = "\n<results>\n <course>$course_id</course> \n <books> \n";

			foreach ( $books as $book ) {

				//construct the XML format for each book
				$xml .="  <book";

				foreach ( $book as $k => $v ){
					$xml .= " $k='$v'";
				}

				$xml .="/> \n";

			}

			$xml .= "\n </books>\n</results>";
			$data['books'] = $xml;

			$this->load->view('welcome_message', $data);

		}
		
		//if the formatted form option is JSON
		else {
			//construct the array that is to be converted to JSON
			$JSONarray = array( 'results' => array( 'course' => $course_id, 'books' => $books ) );

			//sort the array by borrowedcount descending
			//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
			foreach ($JSONarray['results']['books'] as $key => $row) {
				$borrowedcountSort[$key]  = $row['borrowedcount'];
			}

			array_multisort($borrowedcountSort, SORT_DESC, $JSONarray['results']['books']);

			//convert the JSON array to a JSON object
			$JSONobject = json_encode($JSONarray);

			$data['books'] = $JSONobject;
			$this->load->view('welcome_message', $data);
		}

	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */