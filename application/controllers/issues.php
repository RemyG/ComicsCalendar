<?php

class IssuesController extends Controller {
	
	function index($year = null, $month = null)
	{
		$this->month($year, $month);
	}

	private function getComicVineIssue($issueId)
	{
		$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=id:'.$issueId);
		if ($xml->error == 'OK' && $xml->number_of_total_results == '1')
		{
			$issueXml = $xml->results->issue;
			$serieXml = $issueXml->volume;
			$serieTitle = $serieXml->name;
			$serie = SerieQuery::create()->findOneByTitle($serieTitle);
			if ($serie == null)
			{
				$serieCvId = $serieXml->id;
				$serieCvUrl = $serieXml->site_detail_url;				
				$serie = new Serie();
				$serie->setTitle($serieTitle);
				$serie->setCvId($serieCvId);
				$serie->setCvUrl($serieCvUrl);
				$serie->save();
			}

			$issueNumber = $issueXml->issue_number;
			$issueTitle = $issueXml->name;
			$issuePubDate = $issueXml->cover_date;
			$issueCvId = $issueXml->id;
			$issueCvUrl = $issueXml->site_detail_url;

			$issue = IssueQuery::create()->filterBySerie($serie)->filterByTitle($issueTitle)->filterByIssueNumber($issueNumber)->findOne();

			if ($issue == null)
			{
				$issue = new Issue();
				$issue->setTitle($issueTitle);
				$issue->setSerie($serie);
				$issue->setPubDate($issuePubDate);
				$issue->setIssueNumber($issueNumber);
				$issue->setCvId($issueCvId);
				$issue->setCvUrl($issueCvUrl);
				$issue->save();
			}
			return true;

		}
		else
		{
			return false;
		}
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
		$template->set('calendar', $calendar);
		$template->set('firstWday', $first['wday']);
		$template->set('month', $month);
		$template->set('year', $year);
		$template->set('nextMonth', $this->getNextMonth($year, $month));
		$template->set('prevMonth', $this->getPrevMonth($year, $month));
		$template->render();
	}

	function importTwoWeeksIssues()
	{
		$date = date('Ymd');
		$this->importIssuesForDate($date);
		$this->importLastWeekIssues();
		$this->importNextWeekIssues();
	}

	function importLastWeekIssues()
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('-'.$i.' days'));
			$this->importIssuesForDate($date);
		}
	}

	function importNextWeekIssues()
	{
		for ($i = 1 ; $i <= 7 ; $i++)
		{
			$date = date('Ymd', strtotime('+'.$i.' days'));
			$this->importIssuesForDate($date);
		}
	}

	function importIssuesForDate($date)
	{
		$xml = simplexml_load_file('http://www.comicvine.com/api/issues/?api_key='.COMIC_VINE_API_KEY.'&filter=cover_date:'.$date);
		if ($xml->error == 'OK' && (int) $xml->number_of_total_results > 0)
		{
			foreach($xml->results->issue as $issue)
			{
				$this->importIssue($issue);
			}
		}
	}

	function importIssue($issueXml)
	{
		$serieXml = $issueXml->volume;

		$serieCvId = $serieXml->id;
		$serie = SerieQuery::create()->findOneByCvId($serieCvId);
		if ($serie == null)
		{
			$serieTitle = $serieXml->name;
			$serieCvUrl = $serieXml->site_detail_url;
			$serie = new Serie();
			$serie->setTitle($serieTitle);
			$serie->setCvId($serieCvId);
			$serie->setCvUrl($serieCvUrl);
			$serie->save();
		}

		
		$issueCvId = $issueXml->id;
		$issue = IssueQuery::create()->filterByCvId($issueCvId)->findOne();
		if ($issue == null)
		{
			$issueNumber = $issueXml->issue_number;
			$issueTitle = $issueXml->name;
			$issuePubDate = $issueXml->cover_date;
			$issueCvUrl = $issueXml->site_detail_url;
			$issue = new Issue();
			$issue->setTitle($issueTitle);
			$issue->setSerie($serie);
			$issue->setPubDate($issuePubDate);
			$issue->setIssueNumber($issueNumber);
			$issue->setCvId($issueCvId);
			$issue->setCvUrl($issueCvUrl);
			$issue->save();
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