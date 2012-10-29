<?php

class Suggestionsmodel extends CI_Model {

	protected $file = "suggestions.xml";

	function __construct() {
		parent::__construct();
	}

	function getBookSuggestions( $book_id ) {

		$stylesheet = 'getBookSuggestions.xsl';
		$file = new DOMDocument();

		$file->load(  dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . $this->file  );
		$file->saveXML();

		$xsl = new DOMDocument();
		$xsl->load( dirname( __FILE__ ) . '/../' . $this->config->item( 'xml_path' ) . '/xsl/' . $stylesheet );

		$proc = new XSLTProcessor();
		$proc->importStylesheet( $xsl );
		$proc->setParameter( '', 'book_id', $book_id );

		//save the matched book
		$file->saveXML();
		$newXML = $proc->transformToXml( $file );

		return $newXML;

	}

}

?>
