<?php

/**
 * Utils class
 * 
 * Utility functions to be used in the theme files
 * 
 * @package AllThingsHair
 */

class Utils
{

	/**
	 * Prints the variable, object or array content with a <pre> tag and print_r() 
	 * @param any $to_print Variable with content to be printed
	 */
	public static function showme($to_print)
	{
		echo "<pre>" . print_r($to_print, true) . "</pre>";
	}

	public static function showmeHidden($to_print)
	{
		echo "<pre style='display:none;'>" . print_r($to_print, true) . "</pre>";
	}


	/**
	 * Return a placeholder SVG image to keep the aspect ratio of the image while not lazy loaded
	 * @param Int $width Image natural width
	 * @param Int $height Image natural height
	 */
	public static function img_placeholder_src($width = 1000, $height = 1000)
	{
		return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII=";
		//return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$width.' '.$height.'"%3E%3C/svg%3E';
	}

	/**
	 * Get template, include file
	 * @param string $file string file path
	 * @param array $args Extract array parse template as variables
	 */
	public static function get_template($file, $args = array())
	{

		if ($args && is_array($args)) {
			extract($args);
		}

		$filepath = get_template_directory() . '/' . $file . '.php';

		if (!file_exists($filepath)) {
			return;
		}
		include($filepath);
	}

	/**
	 * Prints a script to log in the console via javascript
	 * @param string $message Text message to log in the console
	 * @param string $type The message type (log, warn or error)
	 */
	public static function console_log($message, $type = 'log')
	{
		echo "
			<script>console.{$type}('{$message}');</script>
			";
	}

	/**
	 * Gets the image URL from the assets folder
	 * @param $file_name Image file name with extension
	 * @return String Image URL
	 */
	public static function get_image_asset($file_name)
	{
		return get_template_directory_uri() . "/assets/img/{$file_name}";
	}

	/**
	 * Gets the video URL from the assets folder
	 * @param $file_name Video file name with extension
	 * @return String Video URL
	 */
	public static function get_video_asset($file_name)
	{
		return get_template_directory_uri() . "/assets/video/{$file_name}";
	}
}
