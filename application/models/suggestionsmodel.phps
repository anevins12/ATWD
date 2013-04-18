<?php

class Suggestionsmodel extends CI_Model {

	private $suggestions = array();

	protected $file = "suggestions.xml";

	function __construct() {
		parent::__construct();
		$this->load->library('applicationpath');
	}

	function getBookSuggestions( $suggestion_id ) {

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
		
		//get all suggestions nodes
		if ( $file->getElementsByTagName( 'suggestions' ) ) {
			
			//get book suggestions by book id, on the 'for-id' attribute
			$suggestions = $simplexml->xpath( "suggestions[@for-id='$suggestion_id']/item" );	
		
		#Attempted DOMDocument
		//	$suggestions = $file->getElementsByTagName( 'suggestions' );
		}
		else {
			show_error( "The XML file contains no nodes named 'suggestions'" );
			log_message( 'error', "XML file has no 'suggestions' nodes" );
		}
		
		if ( !$suggestions ) throw new Exception("Invalid Book ID $suggestion_id");
		
		foreach ( $suggestions as $suggestion ) {
		
			$this->suggestions[] = array( 'item' => (string)$suggestion[0],
										  'common' => (string)$suggestion[0]->attributes()->common,
										  'before' => (string)$suggestion[0]->attributes()->before,
										  'same' => (string)$suggestion[0]->attributes()->same,
										  'after' => (string)$suggestion[0]->attributes()->after,
										  'total' => (string)$suggestion[0]->attributes()->total,
										  'isbn' => (string)$suggestion[0]->attributes()->isbn,
										  
										);

		}
		
		
		
		#Attempted DOMDocument
		//set the for-id as type id
		//foreach ( $suggestions as $suggestion ) {

		//	$suggestion->setIdAttribute( 'for-id', true);

		//}

		//validate the document
		//$file->validateOnParse = true;
		//
		//now get the suggestion by its suggestion_id value
		//$suggestion = $file->getElementById( $suggestion_id );
		//
		//if ( !$suggestion ) throw new Exception("Invalid Book ID $suggestion_id");
		//
		//get all of the items within the matched suggestion element
		//if ( $suggestion->getElementsByTagName( 'item' ) ) {
		//	$items = $suggestion->getElementsByTagName( 'item' );
		//}
		//else {
		//	show_error( "The XML file contains no nodes named 'item'" );
		//	log_message( 'error', "XML file has no 'item' nodes" );
		//}
		//
		//if ( $items ) {
		//
		//	foreach ( $items as $item ) {
		//
		//		$this->suggestions[] = array( $item->nodeName => $item->nodeValue ,
		//									  $item->getAttributeNode('common')->nodeName => $this->common = $item->getAttribute('common'),
		//									  $item->getAttributeNode('before')->nodeName => $this->common = $item->getAttribute('before'),
		//									  $item->getAttributeNode('same')->nodeName => $this->common = $item->getAttribute('same'),
		//									  $item->getAttributeNode('after')->nodeName => $this->common = $item->getAttribute('after'),
		//									  $item->getAttributeNode('total')->nodeName => $this->common = $item->getAttribute('total'),
		//									  $item->getAttributeNode('isbn')->nodeName => $this->common = $item->getAttribute('isbn')
		//									);
		//
		//	}

				//}

		return $this->suggestions;

	}
        
        public function suggestions() {
		
            extract( $_GET );
			$format = strtoupper($format);
			
            $this->load->model( 'Booksmodel' );
            $booksmodel = new Booksmodel();
            
            $this->load->model( 'Coursesmodel' );
            $coursesmodel = new Coursesmodel();
            
            $data[ 'format' ] = $format;

            //handle exceptions if there are any
            try {
                    $suggestions = $this->getBookSuggestions( $book_id );
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
                            $JSONobject = json_encode( $JSONarray );
                            $data[ 'service' ] = $JSONobject;
                    }
            }
            //if the inputted book id has not matched with the any node in suggestions.xml, return the error
            else {
                    $data[ 'service' ] = $error;
                    $data[ 'error' ] = true;
            }

            $data[ 'requested' ] = 'suggestions';
            $courses[ 'courses' ] = $coursesmodel->getAllCourses();
            $data[ 'courses' ] = $courses[ 'courses' ];

            if ( $format == 'JSON' || $format == 'XML' ) { 
                
                if ( $format == 'JSON' ) {
                    $data[ 'service' ] = $booksmodel->formatXML( $data );
                }
                
                else if ( $format == 'XML' ) {
                    $data[ 'client' ] = $booksmodel->formatXML( $data );
                }
            }
            
            else {
                $error =  "<?xml version='1.0' encoding='UTF-8'?> \n<results>\n  <error id='500' message='Service Error' /> \n</results>";
                $data[ 'service' ] = $error;
                $data[ 'error' ] = true;
            }

            if ( isset( $submit ) ) {
                return $data;
            }
            else {
                return $data[ 'service' ];
            }
        
        }

}

?>
