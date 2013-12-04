<?php

/**
 * Controller for the Issue class.
 * 
 * @author RemyG
 */
class IssuesController extends Controller {
	
	/**
	 * 
	 * @param string $year
	 * @param string $month
	 */
	function index($year = null, $month = null)
	{
		$this->month($year, $month);
	}

	/**
	 * 
	 * @param string $year
	 * @param string $month
	 */
	function month($year = null, $month = null)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		
		if ($year == null)
		{
			$year = date('Y');
		}
		if ($month == null)
		{
			$month = date('m');
		}

		$dateHelper = $this->loadHelper("Date_helper");
		
		$first = $dateHelper->firstOfMonth($year, $month);
		$last = $dateHelper->lastOfMonth($year, $month);

		$calendar = array();

		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		
		$user = UserQuery::create()->findOneByLogin($userLogin);

		for ($i = $first['mday'] ; $i <= $last['mday'] ; $i++)
		{
			$dayNb = str_pad($i, 2, '0', STR_PAD_LEFT);
			$day = $dateHelper->getDay($year, $month, $dayNb);
			$calendar[$i]['day'] = $day;
			$calendar[$i]['issues'] = array();
			if ($user != null)
			{
				$issues = IssueQuery::create()
							->useSerieQuery()
								->orderByTitle()
								->filterByUser($user)
							->endUse()
							->orderByTitle()
							->findByPubDate($year.'-'.$month.'-'.$dayNb);
				foreach ($issues as $issue)
				{
					$calendar[$i]['issues'][] = $issue;
				}
			}
		}

		$template = $this->loadView('issues_month_view');
		$template->set('user', $user);
		$template->set('calendar', $calendar);
		$template->set('firstWday', $first['wday']);
		$template->set('month', $month);
		$template->set('year', $year);
		$template->set('nextMonth', $dateHelper->getNextMonth($year, $month));
		$template->set('prevMonth', $dateHelper->getPrevMonth($year, $month));
		$template->render();
	}

	/**
	 * Update all existing issues
	 */
	function updateAllIssues()
	{
		$issues = IssueQuery::create()->find();
		foreach ($issues as $issue)
		{
			set_time_limit(30);
			$xml = simplexml_load_file('http://www.comicvine.com/api/issue/4000-'.$issue->getCvId().'/?api_key='.COMIC_VINE_API_KEY);
			if ($xml->error == 'OK' && (int) $xml->number_of_page_results == 1)
			{
				$this->importIssueFromXml($xml->results);
			}
		}
	}

	/**
	 * Retrieve all issues for all the existing series
	 */
	function updateAllSeries()
	{
		$series = SerieQuery::create()->find();
		foreach ($series as $serie)
		{
			$offset = 0;
			do
			{
				set_time_limit(30);
				$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=volume:'.$serie->getCvId().'&offset='.$offset);
				$offset += 100;
				foreach($xml->results->issue as $issueXml)
				{
					$this->saveIssueFromXml($issueXml, $serie);
				}
			}
			while ($xml->error == 'OK' && $xml->number_of_page_results >= 100);
		}
	}

	/**
	 * 
	 */
	function importTwoWeeksStoreIssues()
	{
		$date = date('Ymd');
		$this->importIssuesForDate($date);
		$this->importLastWeekIssues();
// 		$this->importNextWeekIssues();
	}

// 	function importTwoWeeksIssues()
// 	{
// 		$date = date('Ymd');
// 		$this->importIssuesForDate($date);
// 		$this->importLastWeekIssues();
// 		$this->importNextWeekIssues();
// 	}

	/**
	 * 
	 * @param bool $store should the search be based on the store date instead of the cover date
	 */
	private function importLastWeekIssues($store = true)
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('-'.$i.' days'));
			$this->importIssuesForDate($date, 0, $store);
		}
	}

	/**
	 * 
	 * @param bool $store should the search be based on the store date instead of the cover date
	 */
	private function importNextWeekIssues($store = true)
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('+'.$i.' days'));
			$this->importIssuesForDate($date, 0, $store);
		}
	}

	/**
	 * Import all issues for the specified date
	 * 
	 * @param unknown $date
	 * @param number $offset the offset of the search (each API result page can only contain 100 results)
	 * @param bool $store should the search be based on the store date instead of the cover date
	 */
	private function importIssuesForDate($date, $offset = 0, $store = true)
	{
		if ($store)
		{
			$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=store_date:'.$date.'&offset='.$offset);
		}
		else
		{
			$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=cover_date:'.$date.'&offset='.$offset);
		}
		if ($xml->error == 'OK' && (int) $xml->number_of_page_results > 0)
		{
			foreach($xml->results->issue as $issue)
			{
				$this->importIssueFromXml($issue);
			}
			if ((int) $xml->number_of_page_results == 100)
			{
				$this->importIssuesForDate($date, $offset + 100, $store);
			}
		}
	}

	/**
	 * 
	 * 
	 * @param unknown $issueXml
	 */
	private function importIssueFromXml($issueXml)
	{
		$serieXml = $issueXml->volume;
		$serie = $this->saveSerieFromXml($serieXml);
		$issue = $this->saveIssueFromXml($issueXml, $serie);
	}

	/**
	 * Update or create a serie, based on the input XML, then retrieve the issues for this serie
	 * 
	 * @param SimpleXMLElement $serieXml
	 * @return Serie the updated/created serie
	 */
	private function saveSerieFromXml($serieXml)
	{
		$serieCvId = $serieXml->id;
		$serie = SerieQuery::create()->findOneByCvId($serieCvId);

		$importSerie = false;

		if ($serie == null)
		{
			$serie = new Serie();
			$serie->setAddedOn(time());
			$serie->setCvId($serieCvId);
			$importSerie = true;
		}

		$serieTitle = $serieXml->name;
		$serieCvUrl = $serieXml->site_detail_url;

		$serie->setTitle($serieTitle);
		$serie->setCvUrl($serieCvUrl);
		$serie->save();

		if ($importSerie)
		{
			$this->retrieveIssuesForSerie($serie);
		}

		return $serie;
	}

	/**
	 * Update or create an issue, based on the input XML
	 * 
	 * @param SimpleXMLElement $issueXml
	 * @param Serie $serie
	 * @return Issue the updated/created issue
	 */
	private function saveIssueFromXml($issueXml, $serie)
	{
		$issueCvId = $issueXml->id;
		$issue = IssueQuery::create()->filterByCvId($issueCvId)->findOne();

		if ($issue == null)
		{
			$issue = new Issue();
			$issue->setCvId($issueCvId);
		}

		$issueNumber = $issueXml->issue_number;
		$issueTitle = $issueXml->name;
		if ($issueXml->store_date != null && $issueXml->store_date != '')
		{
			$issuePubDate = $issueXml->store_date;
		}
		else if ($issueXml->cover_date != null && $issueXml->cover_date != '')
		{
			$issuePubDate = $issueXml->cover_date;
		}
		else
		{
			$issuePubDate = '0000-00-00';
		}
		
		$issueCvUrl = $issueXml->site_detail_url;
		
		$issue->setTitle($issueTitle);
		$issue->setSerie($serie);
		$issue->setPubDate($issuePubDate);
		$issue->setIssueNumber($issueNumber);		
		$issue->setCvUrl($issueCvUrl);
		$issue->save();

		return $issue;
	}

	/**
	 * Create the serie based on the ComicVine volumet ID if it doesn't exist, then retrieve all issues for this serie 
	 * 
	 * @param int $serieCvId the ComicVine volume ID for this serie
	 */
	function importSerie($serieCvId)
	{
		$serie = SerieQuery::create()->findOneByCvId($serieCvId);
		if ($serie == null)
		{
			set_time_limit(30);
			$xml = simplexml_load_file('http://www.comicvine.com/api/volume/4050-'.$serieCvId.'/?api_key='.COMIC_VINE_API_KEY);
			if ($xml->error == 'OK' && (int) $xml->number_of_page_results == 1)
			{
				$serie = $this->saveSerieFromXml($xml->results);
			}
		}

		$this->retrieveIssuesForSerie($serie);
	}
	
	/**
	 * Retrieve all issues for the specified serie
	 * 
	 * @param Serie $serie
	 */
	function retrieveIssuesForSerie($serie)
	{
		$offset = 0;
		do
		{
			set_time_limit(30);
			$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=volume:'.$serie->getCvId().'&offset='.$offset);
			$offset += 100;
			foreach($xml->results->issue as $issueXml)
			{
				$this->saveIssueFromXml($issueXml, $serie);
			}
		}
		while ($xml->error == 'OK' && $xml->number_of_page_results >= 100);
	}

	/**
	 * Add or remove an issue for the current user
	 * 
	 * @param int $issueId
	 * @param string $add "true" or "false"
	 */
	function toggleIssue($issueId, $add)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		
		$sessionHelper = $this->loadHelper('Session_helper');
		
		$userLogin = $sessionHelper->get('user-login');
		
		$user = UserQuery::create()->findOneByLogin($userLogin);
		$issue = IssueQuery::create()->findPK($issueId);
		
		if ($user != null && $issue != null)
		{
			if ($add == "true")
			{
				$user->addIssue($issue);
				$user->save();
			}
			else
			{
				$user->removeIssue($issue);
				$user->save();
			}
		}
	}
}

?>
