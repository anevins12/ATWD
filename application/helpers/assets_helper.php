<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Expects that all resources are in a assets directory in the root folder.
 *
 * application
 * system
 * index.php
 * assets
 * -- css
 * -- editor
 * -- ico
 * -- images
 * -- img
 * -- js
 * -- media
 * -- smileys
 * -- video
 *
 * Usage:
 *
 *  <?php echo css_url('app.css'); ?>
 *
 * You can also pass in a directory to the method's
 *
 *  <?php echo css_url('directory/app.css'); ?>
 *
 */

// ------------------------------------------------------------------------

/**
 * Helper css_url()
 *
 * Usage:
 *
 *  <?php echo css_url('app.css'); ?>
 *
 * @access public
 * @param string
 * @return string
 */
if ( ! function_exists('css_url'))
{
 function css_url($uri)
 {
  $CI =& get_instance();
  return $CI->config->base_url('assets/css/' . $uri);
 }
}

// ------------------------------------------------------------------------

/**
 * Helper images_url()
 *
 * Usage:
 *
 * @access public
 * @param string
 * @return string
 */
if ( ! function_exists('images_url'))
{
 function images_url($uri)
 {
  $CI =& get_instance();
  return $CI->config->base_url('assets/images/' . $uri);
 }
}

// ------------------------------------------------------------------------

/**
 * Helper img_url()
 *
 * Usage:
 *
 * @access public
 * @param string
 * @return string
 */
if ( ! function_exists('img_url'))
{
 function img_url($uri)
 {
  $CI =& get_instance();
  return $CI->config->base_url('assets/img/' . $uri);
 }
}

// ------------------------------------------------------------------------

/**
 * Helper js_url()
 *
 * Usage:
 *
 * @access public
 * @param string
 * @return string
 */
if ( ! function_exists('js_url'))
{
 function js_url($uri)
 {
  $CI =& get_instance();
  return $CI->config->base_url('assets/js/' . $uri);
 }
}

// ------------------------------------------------------------------------

/**
 * Helper ico_url()
 *
 * Usage:
 *
 * @access public
 * @param string
 * @return string
 */
if ( ! function_exists('ico_url'))
{
 function ico_url($uri)
 {
  $CI = get_instance();
  return $CI->config->base_url('assets/ico/' . $uri);
 }
}

/* ------------------------------------------------------------------------
 * End of file assets_helper.php
 * Location: ./application/helpers/assets_helper.php
 * ------------------------------------------------------------------------
 */ 