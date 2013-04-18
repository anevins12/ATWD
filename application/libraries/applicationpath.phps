<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of serverChecker
 *
 * @author andrew
 */
class Applicationpath {

	public function getApplicationPath() { 

		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			return dirname($_SERVER['SCRIPT_FILENAME']).'/application/';
		}
		
		return dirname( __FILE__ ) . '/../';

	}

}
?>
