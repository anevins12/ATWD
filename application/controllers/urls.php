<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of urls
 *
 * @author andrew
 */
class urls extends CI_Controller {

	function __constuct() {
		parent::__construct();
	}

	public function notFound() {
		$xml =
'<?xml version="1.0" encoding="utf-8"?>
<results>
	<error id="503" message="URL pattern not recognised" />
</results>';

		return $xml;
	}

    //put your code here
}
?>
