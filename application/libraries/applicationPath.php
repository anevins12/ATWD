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
class ApplicationPath {

	public function getApplicationPath() {

		if ( strstr ( $_SERVER['REQUEST_URI'] , '~a2-nevins' ) ) {
			return $this->config->item('uwe_application_path');
		}

		return dirname( __FILE__ ) . '/../';

	}

}
?>
