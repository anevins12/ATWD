<?php

class Suggestionsmodel extends CI_Model {

	private $id;
	private $common;
	private $same;
	private $after;
	private $total;
	private $isbn;

	protected $file = "suggestions.xml";

	function __construct() {
		parent::__construct();
	}

	function getBookSuggestionsReturnXML( $suggestion_id ) {

		$file = new DOMDocument();
		
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) . $this->file );
		}
		else {
			$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		}
		
		$xml = "\n <results> \n <suggestionsfor>$suggestion_id</suggestionsfor> \n  <books> \n   <suggestions>";

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

		//get all of the items within the matched suggestion element
		$items = $suggestion->getElementsByTagName( 'item' );

		foreach ( $items as $item ) {

			$this->id = $item->nodeValue;
			$this->common = $item->getAttribute('common');
			$this->before = $item->getAttribute('before');
			$this->same = $item->getAttribute('same');
			$this->after = $item->getAttribute('after');
			$this->total = $item->getAttribute('total');
			$this->isbn = $item->getAttribute('isbn');

			//constructing xml for each item
			$xml .= "\n   <isbn id='$this->id' common='$this->common' before='$this->before' same='$this->same' after='$this->after' total='$this->total'> $this->isbn </isbn>";

		}
		
		$xml .= "\n </suggestions> \n</results>";

		return $xml;

	}

	function getBookSuggestionsReturnJSON ( $suggestion_id ) {

		$file = new DOMDocument();

		//load the XML file into the DOM, loading statically
		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			$file->load( dirname($_SERVER['SCRIPT_FILENAME']).'/application/' . $this->config->item( 'xml_path' ) . $this->file );
		}
		else {
		$file->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file );
		}

		//get all item nodes
		$suggestions = $file->getElementsByTagName( 'suggestions' );

		//set the for-id as type id
		foreach ( $suggestions as $suggestion ) {
			
			$suggestion->setIdAttribute( 'for-id', true);

			//validate the document
			$file->validateOnParse = true;
		}

		$suggestionArray = array();

		//now get the suggestion by its id value
		$suggestion = $file->getElementById( $suggestion_id );

		//get all of the items within the matched suggestion element
		$items = $suggestion->getElementsByTagName( 'item' );

		foreach ( $items as $item ) {

			$id = $item->nodeValue;
			$common = $item->getAttribute('common');
			$before = $item->getAttribute('before');
			$same = $item->getAttribute('same');
			$after = $item->getAttribute('after');
			$total = $item->getAttribute('total');
			$isbn = $item->getAttribute('isbn');

			//construct the array for each item and its values
			$suggestionArray[] = array( 'isbn' => array( 'id' => $id, 'common' => $common, 'before' => $before, 'same' => $same, 'after' => $after, 'total' => $total, 'isbn_no' => $isbn ) );
			
		}

		//construct the array that is to be converted to JSON
		$JSONarray = array( 'results' => array ( 'suggestionsfor' => $suggestion_id, 'books' => array( 'suggestions' => $suggestionArray ) ) );

		//convert the JSON array to a JSON object
		$JSONobject = json_encode($JSONarray);

		return $JSONobject;

	}

}

?>
