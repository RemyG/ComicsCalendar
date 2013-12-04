<?php

/**
 * Session helper.
 * 
 * @author RemyG
 */
class Session_helper {

	/**
	 * Store the key/value pair in the $_SESSION.
	 * 
	 * @param string $key the key of the pair to store in session
	 * @param mixed $val the value of the pair to store in session
	 */
	function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}
	
	/**
	 * Retrieve the value for this key from the $_SESSION.
	 * 
	 * @param string $key the key of the value to retrieve
	 * @return mixed|NULL the value from the session, or null if the key doesn't exist in the session
	 */
	function get($key)
	{
		if (array_key_exists("$key", $_SESSION))
		{
			return $_SESSION["$key"];
		}
		return null;
	}
	
	/**
	 * Destroy the current session.
	 */
	function destroy()
	{
		session_destroy();
	}

	/**
	 * Return the current user login from the session (key "user-login").
	 * 
	 * @return string|NULL the current user login, or null if no user is logged in
	 */
	function getCurrentUserLogin()
	{
		if (array_key_exists('user-login', $_SESSION))
		{			
			return $_SESSION['user-login'];
		}
		else
		{
			return null;
		}
	}

}

?>