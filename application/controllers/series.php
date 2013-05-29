<?php

class SeriesController extends Controller {
	
	function index()
	{
		$template = $this->loadView('main_view');
		$template->set('pageTitle', 'PFP');
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$template->render();
	}

	function show()
	{
		$series = SerieQuery::create()->orderByTitle()->find();
		$template = $this->loadView('series_show_view');
		$template->set('series', $series);
		$template->render();
	}

}

?>
