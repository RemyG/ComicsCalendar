<?php

class IssuesController extends Controller {
	
	function index($year = null, $month = null)
	{
		$this->month($year, $month);
	}

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

		$first = $this->firstOfMonth($year, $month);
		$last = $this->lastOfMonth($year, $month);

		$calendar = array();

		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		$user = UserQuery::create()->findOneByLogin($userLogin);

		for ($i = $first['mday'] ; $i <= $last['mday'] ; $i++)
		{
			$dayNb = str_pad($i, 2, '0', STR_PAD_LEFT);
			$day = $this->getDay($year, $month, $dayNb);
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
		$template->set('nextMonth', $this->getNextMonth($year, $month));
		$template->set('prevMonth', $this->getPrevMonth($year, $month));
		$template->render();
	}

	function updateAllIssues()
	{
		/*$issues = IssueQuery::create()->find());*/
		$issues = IssueQuery::create()->findByPubDate('2013-06-13');
		/*$issues = IssueQuery::create()->findBySerieId(638);*/
		foreach ($issues as $issue)
		{
			set_time_limit(30);
			$xml = simplexml_load_file('http://www.comicvine.com/api/issue/4000-'.$issue->getCvId().'/?api_key='.COMIC_VINE_API_KEY);
			if ($xml->error == 'OK' && (int) $xml->number_of_page_results == 1)
			{
				$this->importIssue($xml->results);
			}
		}
	}

	function updateAllSeries()
	{
		/*$serie1 = SerieQuery::create()->findPK(411);
		$serie2 = SerieQuery::create()->findPK(388);
		$series = array($serie1, $serie2);*/
		$series = SerieQuery::create()->find();
		foreach ($series as $serie)
		{
			$offset = 0;
			do
			{
				set_time_limit(30);
				$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=volume:'.$serie->getCvId().'&offset='.$offset);
				$offset += 100;
				foreach($xml->results->issue as $issue)
				{
					$this->importIssue($issue);
				}
			}
			while ($xml->error == 'OK' && $xml->number_of_page_results >= 100);
		}
	}

	function importTwoWeeksStoreIssues()
	{
		$date = date('Ymd');
		$this->importIssuesForDate($date);
		$this->importLastWeekIssues();
		$this->importNextWeekIssues();
	}

	/*function importTwoWeeksIssues()
	{
		$date = date('Ymd');
		$this->importIssuesForDate($date);
		$this->importLastWeekIssues();
		$this->importNextWeekIssues();
	}*/

	private function importLastWeekIssues($store = true)
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('-'.$i.' days'));
			$this->importIssuesForDate($date, 0, $store);
		}
	}

	private function importNextWeekIssues($store = true)
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('+'.$i.' days'));
			$this->importIssuesForDate($date, 0, $store);
		}
	}

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
				$this->importIssue($issue);
			}
			if ((int) $xml->number_of_page_results == 100)
			{
				$this->importIssuesForDate($date, $offset + 100, $store);
			}
		}
	}

	private function importIssue($issueXml)
	{
		$serieXml = $issueXml->volume;
		$serie = $this->getSerie($serieXml);
		$issue = $this->getIssue($issueXml, $serie);
	}

	private function getSerie($serieXml)
	{
		$serieCvId = $serieXml->id;
		$serie = SerieQuery::create()->findOneByCvId($serieCvId);

		if ($serie == null)
		{
			$serie = new Serie();
			$serie->setAddedOn(time());
			$serie->setCvId($serieCvId);
		}

		$serieTitle = $serieXml->name;
		$serieCvUrl = $serieXml->site_detail_url;

		$serie->setTitle($serieTitle);
		$serie->setCvUrl($serieCvUrl);
		$serie->save();

		return $serie;
	}

	private function getIssue($issueXml, $serie)
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

	function toggleIssue($issueId, $add)
	{
		$this->hiddenInitiate();
		$this->hiddenKeepAlive();
		$sessionHelper = $this->loadHelper('Session_helper');
		$userLogin = $sessionHelper->get('user-login');
		$user = UserQuery::create()->findOneByLogin($userLogin);
		$issue = IssueQuery::create()->findPK($issueId);
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

	private function firstOfMonth($year, $month) {
		return getdate(mktime(0, 0, 0, $month, 1, $year));
	}

	private function lastOfMonth($year, $month) {
		return getdate(mktime(0, 0, 0, $month+1, 0, $year));
	}

	private function getDay($year, $month, $day)
	{
		return getdate(mktime(0, 0, 0, $month, $day, $year));
	}

	private function getNextMonth($year, $month)
	{
		return date('Y-m', mktime(0, 0, 0, $month + 1, 1, $year));
	}

	private function getPrevMonth($year, $month)
	{
		return date('Y-m', mktime(0, 0, 0, $month - 1, 1, $year));
	}

}

?>
