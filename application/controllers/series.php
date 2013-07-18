<?php

/**
 * 
 * @author remyg
 *
 */
class SeriesController extends Controller {
	
	/**
	 * 
	 */
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
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->getCurrentUserLogin();
		if ($userLogin != null)
		{
			$user = UserQuery::create()->findOneByLogin($userLogin);
			if ($user != null)
			{
				$template->set('user', $user);
				$userIssues = IssueQuery::create()
							->filterBySerie($serie)
							->filterByUser($user)
							->orderByPubDate('desc')
							->orderByIssueNumber('desc')
							->find();
				$template->set('userIssues', $userIssues);
			}
		}
		$template->set('serie', $serie);
		$template->set('issues', $issues);
		$template->render();
	}

	/**
	 * 
	 */
	function showAll()
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$series = SerieQuery::create()->orderByTitle()->find();
		$template = $this->loadView('series_showall_view');
		$template->set('series', $series);
		$template->render();
	}

	/**
	 * 
	 */
	function manage()
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();

		$template = $this->loadView('series_manage_view');

		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		if ($userLogin == null)
		{
			$this->redirect("users/login");
		}
		$user = UserQuery::create()->findOneByLogin($userLogin);

		if ($user->getLastSeenOn() != null)
		{
			$c = new Criteria();
			$crit0 = $c->getNewCriterion(SeriePeer::ADDED_ON, $user->getLastSeenOn(), CRITERIA::GREATER_THAN);
			$c->add($crit0);
			$c->addAscendingOrderByColumn(SeriePeer::TITLE); 
			$newSeries = SeriePeer::doSelect($c);
			$template->set('newSeries', $newSeries);
		}

		$user->setLastSeenOn(time());
		$user->save();

		$series = SerieQuery::create()->orderByTitle()->find();
		
		$template->set('user', $user);
		$template->set('series', $series);
		$template->render();
	}

	/**
	 * 
	 * @param int $serieId
	 * @param string $add "true" (add this series to the current user) or "false" (remove this serie from the current user)
	 */
	function toggleSerie($serieId, $add)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		$user = UserQuery::create()->findOneByLogin($userLogin);
		$serie = SerieQuery::create()->findPK($serieId);
		if ($user != null && $serie != null)
		{
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
		else
		{
			echo 'no-user-serie';
		}
	}

}

?>
