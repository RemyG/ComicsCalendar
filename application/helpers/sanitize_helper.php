<?php

/**
 * String sanitizer helper.
 * 
 * @author RemyG
 */
class Sanitize_helper {

	/**
	 * Clean an input string by stripping out the Javascript, the HTML tags, the style tags and the multi-line comments.
	 * 
	 * @param string $input the string to sanitize
	 * @return string the sanitized string
	 */
	function cleanInput($input)
	{
		if (get_magic_quotes_gpc())
		{
			$input = stripslashes($input);
		}
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
		$output = preg_replace($search, '', $input);
		return $output;
	}

	/**
	 * Clean an input array or string by stripping out the Javascript, the HTML tags, the style tags and the multi-line comments.
	 * 
	 * @param string|array $input the string or array of strings to sanitize
	 * @return string|array the string or array of sanitized elements
	 */
	function sanitize($input)
	{
		if (is_array($input))
		{
			foreach($input as $var=>$val)
			{
				$output[$var] = $this->sanitize($val);
			}
		}
		else
		{
			$output  = $this->cleanInput($input);
		}
		return $output;
	}

}

?>