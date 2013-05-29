<?php

class MainController extends Controller {
	
	function index()
	{
		$template = $this->loadView('main_view');
		$template->set('pageTitle', 'PFP');
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$template->render();
	}
    
}

?>
