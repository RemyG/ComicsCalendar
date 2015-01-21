1. Edit the file `application/config/comicvine-sample.php` with 1 constant 
	(you'll need to ask for an API key [from ComicVine](http://www.comicvine.com/api/)):

		<?php
		define('COMIC_VINE_API_KEY', 'you_comicvine_api_key');
		
	then rename it to `application/config/comicvine.php`.

2. Edit the file `application/config/recaptcha-sample.php` with 2 constants 
	(you'll need to ask for a key [from ReCaptcha](https://www.google.com/recaptcha)):

		<?php
		define('RECAPTCHA_PUBLIC_KEY', 'your_recaptcha_public_key');
		define('RECAPTCHA_PRIVATE_KEY', 'your_recaptcha_private_key');
	
	then rename it to `application/config/recaptcha-sample.php`.

3. Edit the file `application/config/cookies-sample.php` with 1 constant 
	(any random string that'll allow you to salt the cookies information):

		<?php
		define('SESSION_SALT', 'your_session_salt');

	then rename it to `application/config/cookies.php`.

4. Edit the file `application/config/propel-sample.yaml` and set the correct
	`dsn`, `user` and `password` values for the DB connection, then rename
	it to `application/config/propel.yaml`

5. Edit the file `application/config/config-sample.php` and set the constants 
	`BASE_URL` and `DOMAIN` according to your settings, then rename it to 
	`application/config/config.php`

6. Create a Cron task calling `cron-updater.php` every day.