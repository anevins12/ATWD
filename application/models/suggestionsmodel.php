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
		
		//get all item nodes
		$suggestions = $file->getElementsByTagName( 'suggestions' );

		//set the for-id as type id
		foreach ( $suggestions as $suggestion ) {

			$suggestion->setIdAttribute( 'for-id', true);

		}

		//validate the document
		$file->validateOnParse = true;

		//now get the suggestion by its id value
		$suggestion = $file->getElementById( $suggestion_id );

		if ( !$suggestion ) throw new Exception("Invalid Book ID $suggestion_id");

		//get all of the items within the matched suggestion element
		$items = $suggestion->getElementsByTagName( 'item' );


		foreach ( $items as $item ) {

			$this->suggestions[] = array( $item->nodeName => $item->nodeValue ,
								  $item->getAttributeNode('common')->nodeName => $this->common = $item->getAttribute('common'),
								  $item->getAttributeNode('before')->nodeName => $this->common = $item->getAttribute('before'),
								  $item->getAttributeNode('same')->nodeName => $this->common = $item->getAttribute('same'),
								  $item->getAttributeNode('after')->nodeName => $this->common = $item->getAttribute('after'),
								  $item->getAttributeNode('total')->nodeName => $this->common = $item->getAttribute('total'),
								  $item->getAttributeNode('isbn')->nodeName => $this->common = $item->getAttribute('isbn')
								);

		}

		return $this->suggestions;

	}

}

?>
