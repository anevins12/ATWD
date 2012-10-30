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
		$books = $this->Booksmodel->getBooksByCourseIdReturnXML("CC100");
		$books = $this->Booksmodel->getBooksByCourseIdReturnJSON("CC100");
		$books = $this->Booksmodel->getBookDetailsReturnXML("1715");
		$books = $this->Suggestionsmodel->getBookSuggestionsReturnXML("483");
		$books = $this->Booksmodel->updateBorrowedData("51390", "CC140");
		$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */