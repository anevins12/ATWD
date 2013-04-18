<?php
/**
* The Coursesmodel holds all queries that occur on the courses.xml file
*
*
* @author_name  Andrew Nevins
* @author_no    09019549 
* @link         http://isa.cems.uwe.ac.uk/~a2-nevins/atwd/application/models/coursesmodel.php
*/
class Coursesmodel extends CI_Model {

	protected $file = "courses.xml";
	private $courses = array();

	function __construct() {
		parent::__construct();		
	}
	
/**
 * Takes all courses from course.xml 
 *
 * @access	public
 * @return	array
 */
	function getAllCourses() {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically

		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) .  $this->file );
		}
		else {
			$file->load( dirname(__FILE__) . '/../' . $this->config->item('xml_path') . $this->file );
		}

		$courses = $file->getElementsByTagName('course');

		foreach ( $courses as $course ) {
			$this->courses[] = array( 'name' => $course->nodeValue,
									  'id' => $course->getAttribute('id'),
									  'school' => $course->getAttribute('school') 
									);
		}

		return $this->courses;

	}

/**
 * Using the booksmodel, this function constructs XML that contains books that have been matched with a particular course ID
 *
 * @access	public
 * @return	array if is called by client | string if called by service | json if requested format is json
 */      
	public function course() { 
            
		extract( $_GET );
		$course_id = mb_strtoupper($course_id);
		
		$invalid_course = false;
		 
		$this->load->model( 'Booksmodel' );
		$data[ 'error' ] = false;
		$data[ 'json' ] = false;
		$data[ 'requested' ] = 'course';
		$data[ 'format' ] = strtoupper( $format );

		try {
			$this->checkCourseID( $course_id ); 
		}
		catch ( Exception $e ) {
			$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='501' message='" . $e->getMessage() ."' /> \n</results>";
			$data[ 'service' ] = $error;
			$data[ 'error' ] = true;
			$invalid_course = true;
		} 
	
		$booksmodel = new Booksmodel();
		
		if ( !$invalid_course ) { 
			try { 
				$books = $booksmodel->getBooksByCourseId( $course_id );
			}
			catch ( Exception $e ) {
				//if no books are found, but the course ID is legitimate, create a custom error not specified in the brief
				$error =  "<?xml version='1.0' encoding='utf-8'?> \n<results>\n  <error id='504' message='" . $e->getMessage() ."' /> \n</results>";
				$data[ 'service' ] = $error; 
				$data[ 'client' ]['client'] = $error;
				$data[ 'error' ] = true; 
			}
		}

		if ( isset( $books ) ) {

			//sort the array by borrowedcount descending
			//inspired by a comment on http://php.net/manual/en/function.array-multisort.php
			foreach ( $books as $k => $row ) {
					$borrowedcountSort[ $k ]  = $row[ 'borrowedcount' ];
			}

			array_multisort( $borrowedcountSort, SORT_DESC, $books );

			//if the selected form format is XML
			if ( $data[ 'format'] == 'XML' ) {

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
					$error =  "<?xml version='1.0' encoding='UTF-8'?> \n<results>\n  <error id='500' message='Service Error' /> \n</results>";
					$data[ 'service' ] = $error;
					$data[ 'error' ] = true;
			}
			
		}

		if ( !$data[ 'error' ] ) {
			$data[ 'client' ] = $booksmodel->formatXML( $data );
		}

		if ( isset( $submit ) ) { 
			return $data;
		}
                
		else { 
			return $data[ 'service' ];
		}
		
	}
	
/**
 * Checks whether the course ID exists within course.xml
 *
 * @access	public
 * @param	string
 * @return	boolean
 */      
	public function checkCourseId( $course_id ) {

		$course = array();
		//get all courses and store in the array $courses
		$courses = $this->getAllCourses();

		//construct xml to hold all courses
		$xml = "<courses>";

		foreach ( $courses as $course ) {

			$xml .= "\n <course id='" . $course['id'] . "' />";

		}
		
		$xml .= "\n </courses>";

		//using SimpleXML, load in the constructed xml that contains all courses
		$xml = simplexml_load_string($xml);
		
		//try and grab the course id from the constructed xml, that matches the parameter course id
		$course = $xml->xpath("//*[@id='$course_id']");

		if ( !empty ( $course ) ) {
			return true;
		}

		throw new Exception("Invalid Course ID $course_id.");

	}
        
}

?>
