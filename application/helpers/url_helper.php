<?php

/**
 * URL helper.
 * 
 * @author RemyG
 */
class Url_helper {

	/**
	 * Return the BASE_URL constant.
	 * 
	 * @return string the BASE_URL constant
	 */
	function base_url()
	{
		return BASE_URL;
	}
	
	/**
	 * Return the nth segment from the current URL, split by "/"
	 * 
	 * @param int $seg the index of the segment we're looking for
	 * @return string|boolean the value of the nth segment, or false if $seg is not an integer or if the URL doesn't have an nth segment.
	 */
	function segment($seg)
	{
		if(!is_int($seg)) return false;
		
		$parts = explode('/', $_SERVER['REQUEST_URI']);
	    return isset($parts[$seg]) ? $parts[$seg] : false;
	}
	
}

?>