<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cache
 *
 * @author andrew
 */
class cache {

	private $cachedXML;

	public function cacheFile( $xml, $cacheFileName ) {

		$cacheName =  dirname( __FILE__ ) . '/../cache/' . $cacheFileName;
		
		//http://stackoverflow.com/questions/6907265/how-to-cache-xml-file-in-php
		if ( !file_exists( $cacheName ) ) {

			file_put_contents( $cacheName, $xml );

		}

		$this->cachedXML = file_get_contents ( $cacheName ) ;

	}


	

}
?>
