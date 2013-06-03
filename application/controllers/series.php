<?php

class SeriesController extends Controller {
	
	function index()
	{
		$this->showAll();
	}

	function show($id)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$serie = SerieQuery::create()->findPK($id);
		$query = IssueQuery::create()->orderByPubDate('desc')->orderByIssueNumber('desc');
		$issues = $serie->getIssues($query);
		$template = $this->loadView('series_show_view');
		$template->set('serie', $serie);
		$template->set('issues', $issues);
		$template->render();
	}

	function showAll()
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$series = SerieQuery::create()->orderByTitle()->find();
		$template = $this->loadView('series_showall_view');
		$template->set('series', $series);
		$template->render();
	}

	function manage()
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		$user = UserQuery::create()->findOneByLogin($userLogin);
		if (isset($_POST['updateseries']))
		{
			$sanitizer = $this->loadHelper('Sanitize_helper');
			$post = $sanitizer->sanitize($_POST);
			foreach($post['serie'] as $serieId)
			{
				echo $serieId;
			}
		}		
		$user = UserQuery::create()->findOneByLogin($userLogin);
		$series = SerieQuery::create()->orderByTitle()->find();
		$template = $this->loadView('series_manage_view');
		$template->set('user', $user);
		$template->set('series', $series);
		$template->render();
	}

	function updateSerie($serieId, $add)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		$user = UserQuery::create()->findOneByLogin($userLogin);
		$serie = SerieQuery::create()->findPK($serieId);
		if ($add == "true")
		{
			echo 'add';
			$user->addSerie($serie);
			$user->save();
		}
		else
		{
			echo 'remove';
			$user->removeSerie($serie);
			$user->save();
		}
	}

}

?>
