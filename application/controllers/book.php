<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Book extends CI_Controller {

	function __constuct() {

		parent::__construct();
	}

	public function index() {
		$this->load->view('book/index.php', $data);
	}

	public function detail( $id ) {

		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();

		$data = $booksmodel->formatBookDetails();
		$this->format( $data );

		$this->load->view('book/index', $data);
		
	}

}


?>