<?php

/**
 * Controller class
 *
 * @author RemyG
 * @license MIT
 */
class Controller {
	
	public function loadModel($name)
	{
		require(APP_DIR .'models/'. strtolower($name) .'.php');

		$model = new $name;
		return $model;
	}
	
	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}
	
	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}
	
	public function loadHelper($name)
	{
		require(APP_DIR .'helpers/'. strtolower($name) .'.php');
		$helper = new $name;
		return $helper;
	}
	
	public function redirect($loc)
	{		
		header('Location: '. BASE_URL . $loc);
	}
    
	function hiddenInitiate()
	{
		$logged_in = false;
		if(isset($_SESSION['user-login']))
		{
			$logged_in = true;
		}
		// Check that cookie is set
		if(isset($_COOKIE['auth_key']))
		{
			$auth_key = $_COOKIE['auth_key'];

			if($logged_in === false)
			{
				// Select user from database where auth key matches (auth keys are unique)
				$user = UserQuery::create()->findOneByAuthKey($auth_key);				
				if($user == null)
				{
					// If auth key does not belong to a user delete the cookie
					setcookie("auth_key", "", time() - 3600);
				}
				else
				{
					$this->hiddenLogin($user->getLogin(), $user->getPassword(), true);
				}
			}
			else
			{
				setcookie("auth_key", "", time() - 3600);
			}
		}
	}

	function hiddenLogin($username, $password, $remember)
	{
		$user = UserQuery::create()->findOneByLogin($username);
		if ($user == null)
		{
			$user = UserQuery::create()->findOneByEmail($username);
		}
		if ($user != null)
		{
			// Check if user wants account to be saved in cookie
			if($remember)
			{
				// Generate new auth key for each log in (so old auth key can not be used multiple times in case 
				// of cookie hijacking)
				$cookie_auth = $this->rand_string(10).$user->getLogin();
				$auth_key = $this->session_encrypt($cookie_auth);
				$user->setAuthKey($auth_key);
				$user->save();
				setcookie("auth_key", $auth_key, time() + 60 * 60 * 24 * 7, "/", DOMAIN, false, true);
			}
			// Assign variables to session
			session_regenerate_id(true);
			$_SESSION['user-id'] = $user->getId();
			$_SESSION['user-login'] = $user->getLogin();
			$_SESSION['user-lastactive'] = time();
			return true;
		}
		return false;
	}

	// Check if session is still active and if it keep it alive
	function hiddenKeepAlive()
	{
		// If session is supposed to be saved or remembered ignore following code
		if(!isset($_COOKIE['auth_key']))
		{
			if(array_key_exists('user-lastactive', $_SESSION) && !empty($_SESSION['user-lastactive']))
			{
				$oldtime = $_SESSION['user-lastactive'];
				$currenttime = time();
                // this is equivalent to 30 minutes
				$timeoutlength = 30 * 600;
				if($oldtime + $timeoutlength >= $currenttime)
				{
					// Set new user last active time
					$_SESSION['user-lastactive'] = $currenttime;
				}
				else
				{
					// If session has been inactive too long logout
					$this->hiddenLogout();
				}
			}
		}
	}

	function hiddenLogout()
	{
		setcookie("auth_key", "", time() - 3600);
		// Need to delete auth key from database so cookie can no longer be used
		$username = $_SESSION['user-login'];
		$user = UserQuery::create()->findOneByLogin($username);
		if ($user != null)
		{
			$user->setAuthKey(0);
			$user->save();
			unset($_SESSION['user-id']);
			unset($_SESSION['user-login']);;
			unset($_SESSION['user-lastactive']);
			session_unset();
			session_destroy(); 
			return true;
		}
		return false;
	}

	function session_encrypt($string)
	{
		$salt = SESSION_SALT;
		$string = md5($salt.$string);
		return $string;
	}

	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen( $chars );
		$str = '';
		for( $i = 0; $i < $length; $i++ )
		{
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		return $str;
	}

}

?>