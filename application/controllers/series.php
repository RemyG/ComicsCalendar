<?php

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;

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
			$newSeries = SerieQuery::create()
				->filterByAddedOn($user->getLastSeenOn(), CRITERIA::GREATER_THAN)
				->orderByTitle()
				->find();
			$template->set('newSeries', $newSeries);
		}

		$user->setLastSeenOn(time());
		$user->save();
				
		$template->set('user', $user);
		$template->render();
	}
        
	function managebyletter($letter)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
				
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		if ($userLogin == null)
		{
			$error = array("error" => "User is not connected");
			return json_encode($error);
		}
		$user = UserQuery::create()->findOneByLogin($userLogin);
		
		$con = Propel::getWriteConnection(\Map\SerieTableMap::DATABASE_NAME);
		$sql = "SELECT * FROM comics_serie LEFT JOIN comics_user_serie "
			. "ON comics_serie.id = comics_user_serie.serie_id and comics_user_serie.user_id = ? "
			. "WHERE lower(comics_serie.title) REGEXP ? "
			. "ORDER BY comics_serie.title ASC";
		
		$stmt = $con->prepare($sql);
		$stmt->bindParam(1, $user->getId());
		if (preg_match('/^[0-9a-zA-Z]$/', $letter) === 1) {
			$stmt->bindValue(2, '^'.strtolower($letter).'.*', PDO::PARAM_STR);
		}
		else {
			$stmt->bindValue(2, '^[^0-9a-z].*', PDO::PARAM_STR);
		}
		
		if ($stmt->execute()) {
			$series = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		return json_encode($series);
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
