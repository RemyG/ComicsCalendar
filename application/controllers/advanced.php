<?php

class AdvancedController extends Controller {
	
	function index()
	{
		$template = $this->loadView('advanced_view');
		$template->render();
	}

}

?>