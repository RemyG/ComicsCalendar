<?php 

/*
 * Base URL including trailing slash (e.g. http://localhost/)
 */
define('BASE_URL', 'http://comics.local/');

/*
 * Domain, used to store the connection cookie
 */
define('DOMAIN', 'comics.local');

/*
 * Default author name, displayed as meta author
 */
define('DEFAULT_AUTHOR', 'Rémy Gardette');

/*
 * Default page title, displayed in head > title
 */
define('DEFAULT_TITLE', 'Comics Calendar');

/*
 * Default page description, displayed as meta description
 */
define('DEFAULT_DESCRIPTION', 'Comics Calendar - The online calendar to follow the new issues from your favorite comic books.');


// DON'T EDIT BELOW THIS

/*
 * Default controller to load
 */
define('DEFAULT_CONTROLLER', 'issues');

/*
 * Controller used for errors (e.g. 404, 500 etc)
 */
define('ERROR_CONTROLLER', 'error');

?>