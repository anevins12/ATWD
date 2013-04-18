<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* The Books controller provides access to all API calls and the data they return
*
*
* @author_name  Andrew Nevins
* @author_no    09019549 
* @link         http://isa.cems.uwe.ac.uk/~a2-nevins/atwd/application/controllers/books.php
*/
class Books extends CI_Controller {

	function __constuct() {		
		parent::__construct();
	}
/**
 * The default index function provides the view with initialising variables and loads the default view
 *
 * @access	public
 */
	public function index() {
		$data[ 'format' ] = '';
		$data[ 'service' ] = '';
		$data[ 'client' ][ 'client' ] = ''; 
		$data[ 'courses' ] = $this->courses(); 
                
		$this->load->view( 'client/index', $data );
	}
	
/**
 * Uses GET paramters to grab books by course ID
 *
 * @access	public
 */
	public function course() { 
		extract( $_GET );          
		$this->load->model( 'booksmodel' );
		$booksmodel = new Booksmodel();
                
		$this->load->model( 'coursesmodel' );
		$coursesmodel = new Coursesmodel();
                
		$data = $coursesmodel->course(); 
		
		if ( isset( $data[ 'error' ] ) && $data[ 'error' ] && !is_string($data) ) {        
			$data[ 'client' ] = $booksmodel->formatXML( $data[ 'service' ] );
		}
		
		if ( isset( $submit ) ) { 
			$this->load->view( 'client/index', $data );
		}
		else { 
			echo $data;
		}
	}
	
/**
 * Gets all courses from course.xml 
 *
 * @access	public
 * @return	array
 */
	public function courses() {
		$this->load->model( 'coursesmodel' );
		$coursesmodel = new Coursesmodel();
		$data = $coursesmodel->getAllCourses();
		
		return $data;
	}
	
/**
 * Uses GET parameters to get the details of a book, from the book ID. It then loads the view or echos data.
 *
 * @access	public
 */
	public function detail( $book_id = null ) { 
		extract( $_GET );
		
		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();
		$data = $booksmodel->formatBookDetails();
                
		$data[ 'courses' ] = $this->courses();
                
		if ( $format == 'JSON' ) {
			$data[ 'service' ] = $booksmodel->formatXML( $data );
		}
		else {
			$data[ 'client' ] = $booksmodel->formatXML( $data );
		}
                
		if ( isset( $data[ 'error'] ) ) {
			$data[ 'client' ] = $booksmodel->formatXML( $data );
		}
		
		if ( isset( $submit ) ) { 
			$this->load->view( 'client/index', $data );
		}
		else { 
			echo $data[ 'service' ];
		}
	}
	
/**
 * Uses POST parameters to update a book's borrowed count. Loads the view or echos data.
 *
 * @access	public
 */
	public function borrow() {	
		extract( $_POST );
                
		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();
		$data = $booksmodel->borrow(); 
                
		if ( isset( $submit ) ) {
			$this->load->view( 'client/index', $data );
		}
		else { 
			echo $data['service'];                   
		}
	}

/**
 * Uses GET parameters to get the suggestions of a book, from the book ID. It then loads the view or echos data.
 *
 * @access	public
 */
	public function suggestions() {
		extract( $_GET );
                
		$this->load->model( 'Booksmodel' );
		$booksmodel = new Booksmodel();
                
		$this->load->model( 'suggestionsmodel' );
		$suggestionsmodel = new Suggestionsmodel();   
                
		$data = $suggestionsmodel->suggestions();
		
		if ( isset( $data[ 'error'] ) && !is_string( $data ) ) {
			$data[ 'client' ] = $booksmodel->formatXML( $data );
		}
		
		if ( isset( $submit ) ) { 
			$this->load->view( 'client/index', $data );
		}
		else {
			echo $data;
		}		
	}

}
/* End of file books.php */
/* Location: ./application/controllers/books.php */