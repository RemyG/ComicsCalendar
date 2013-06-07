<?php

class Session_helper {

	function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}
	
	function get($key)
	{
		return $_SESSION["$key"];
	}
	
	function destroy()
	{
		session_destroy();
	}

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