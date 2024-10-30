<?php
/*
Plugin Name: Iframe Lazy Load
Description: Lazy load iframes
Version: 1.0.0
Author: Bryan Miller
Author URI: https://profiles.wordpress.org/bryanpaulmiller/
Copyright: Bryan Miller
*/

if(!class_exists('Iframe_Lazy_Load'))
{
	class Iframe_Lazy_Load
	{

		/*--------------------------------------------*
		 * Constants
		 *--------------------------------------------*/
		const name = 'Iframe Lazy Load';
		const slug = 'iframe-lazy-load';
		const version = '1.0.0';

		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{

			if ( !is_admin() )
			{

				// Load JavaScript
				add_action('wp_enqueue_scripts', array(&$this, 'iframe_enqueue_script'));

				// Find all iframe tags and replace the src attribute with data-src
				add_filter('the_content', array(&$this, 'iframe_replace_src'));

			} // END if

		} // END public function __construct


		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing

		} // END public static function activate


		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing

		} // END public static function deactivate


		/**
		 * Enqueue JavaScript
		 */
		function iframe_enqueue_script()
		{
		    wp_enqueue_script( self::slug . '-script', plugins_url('js/iframe-lazy-load.js', __FILE__), array(), self::version, true);

		} // END iframe_enqueue_script


		/**
		 * Find all iframe tags and replace the src attribute with data-src
		 * We will replace the src attribute using JavaScript
		 * @param  string $content
		 * @return string
		 */
		function iframe_replace_src( $content )
		{
		    $content = trim(preg_replace('/\s\s+/', ' ', $content));
			$content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
		    $dom = new DOMDocument();
		    @$dom->loadHTML($content);

		    foreach ($dom->getElementsByTagName('iframe') as $iframe)
			{
		        $src = $iframe->getAttribute('src');
		        $iframe->setAttribute( 'data-src', $src );
		        $iframe->removeAttribute("src");
		    }

		    $content = $dom->saveHTML();

		    // prevent tags and the doctype from being added to the HTML string automatically
		    $content = preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $content);

		    return $content;

		} // END iframe_replace_src


	} // END class Iframe_Lazy_Load
} // END if(!class_exists('Iframe_Lazy_Load'))

if(class_exists('Iframe_Lazy_Load'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Iframe_Lazy_Load', 'activate'));
	register_deactivation_hook(__FILE__, array('Iframe_Lazy_Load', 'deactivate'));

	// instantiate the plugin class
	$iframe_lazy_load = new Iframe_Lazy_Load();

} // END if(class_exists('Iframe_Lazy_Load'))
