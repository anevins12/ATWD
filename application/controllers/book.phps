<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* The Coursesmodel holds all queries that occur on the courses.xml file
*
*
* @author_name  Andrew Nevins
* @author_no    09019549 
* @link         http://isa.cems.uwe.ac.uk/~a2-nevins/atwd/application/controllers/book.php
*/
class Book extends CI_Controller {

	function __constuct() {
		parent::__construct();
	}
	
/**
 * Loads the view and sends over an array containing a book id
 *
 * @access	public
 */
	public function detail( $id ) {
		
		$data['id'] = $id;
		$this->load->view('book/index', $data);
	
	}

}

/* End of file book.php */
/* Location: ./application/controllers/book.php */

?>