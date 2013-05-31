<?php

class Sanitize_helper {

	function cleanInput($input)
	{
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
		$output = preg_replace($search, '', $input);
		return $output;
	}

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
			if (get_magic_quotes_gpc())
			{
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = $input;
		}
		return $output;
	}

}

?>