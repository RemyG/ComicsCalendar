<?php

class UsersController extends Controller {
	
	function index()
	{
		$this->login();
	}

	function login()
	{
		$this->loadPlugin('recaptchalib');
		if (isset($_POST['formlogin']))
		{
			$sanitizer = $this->loadHelper('Sanitize_helper');
			$post = $sanitizer->sanitize($_POST);
			if (isset($post['login']) && $post['login'] !== ''
				&& isset($post['password']) && $post['password'] !== '')
			{
				$login = $post['login'];
				$password = $post['password'];
				$remember = array_key_exists('rememberme', $post);
				if ($this->hiddenLogin($login, $password, $remember))
				{
					$this->redirect('');
				}
			}
		}		
		$template = $this->loadView('users_login_view');
		$template->render();
	}

	function signup()
	{
		$this->loadPlugin('recaptchalib');
		if (isset($_POST['formsignup']))
		{
			$sanitizer = $this->loadHelper('Sanitize_helper');
			$post = $sanitizer->sanitize($_POST);
			if (isset($post['login']) && $post['login'] !== ''
				&& isset($post['email']) && $post['email'] !== ''
				&& isset($post['confirmEmail']) && $post['confirmEmail'] !== ''
				&& isset($post['password']) && $post['password'] !== ''
				&& isset($post['confirmPassword']) && $post['confirmPassword'] !== '')
			{
				$resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
					$_SERVER["REMOTE_ADDR"],
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);

				if (!$resp->is_valid)
				{
					// What happens when the CAPTCHA was entered incorrectly
					die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
					"(reCAPTCHA said: " . $resp->error . ")");
				} else {
					// Your code here to handle a successful verification
					$login = $post['login'];
					$password = $post['password'];
					$confirmPassword = $post['confirmPassword'];
					$email = $post['email'];
					$confirmEmail = $post['confirmEmail'];

					if ($password != $confirmPassword)
					{
						echo 'pass';
					}
					else if ($email != $confirmEmail)
					{
						echo 'email';
					}
					else {
						$user = UserQuery::create()->findOneByLogin($login);
						if ($user != null)
						{
							echo 'user with login';
						}
						else
						{
							$user = UserQuery::create()->findOneByEmail($email);
							if ($user != null)
							{
								echo 'user with email';
							}
							else
							{
								$user = new User();
								$user->setLogin($login);
								$user->setEmail($email);
								$password = crypt($password, '$5$rounds=5000$'.md5($password).'$');
								$user->setPassword($password);
								$user->save();
								$sessionHelper = $this->loadHelper('Session_helper');
								$sessionHelper->destroy();
								session_start();
								$sessionHelper->set('user-login', $user->getLogin());
								$this->redirect('');
							}
						}
					}
				}
			}
		}
		
		$template = $this->loadView('users_login_view');
		$template->render();
	}

	function logout()
	{
		$this->hiddenLogout();
		$this->redirect('');
	}

}

?>
