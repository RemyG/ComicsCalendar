1. Create a file `application/config/comicvine.php` with 1 constant (you'll need to ask for an API key [from ComicVine](http://www.comicvine.com/api/)):

		<?php
		define('COMIC_VINE_API_KEY', 'you_comicvine_api_key');

2. Create a file `application/config/recaptcha.php` with 2 constants (you'll need to ask for a key [from ReCaptcha](https://www.google.com/recaptcha)):

		<?php
		define('RECAPTCHA_PUBLIC_KEY', 'your_recaptcha_public_key');
		define('RECAPTCHA_PRIVATE_KEY', 'your_recaptcha_private_key');

3. Create a file `application/config/cookies.php` with 1 constant (any random string that'll allow you to salt the cookies information):

		<?php
		define('SESSION_SALT', 'your_session_salt');

4. Edit the file `application/build/conf/comicslist-conf.php` and set the correct DSN, username and password for the DB connection.

5. Edit `application/config/config.php` and set the constants `BASE_URL` and `DOMAIN` according to your settings.

6. Create a Cron task calling `cron-updater.php` every day.