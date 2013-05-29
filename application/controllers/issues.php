<?php

class IssuesController extends Controller {
	
	function index($year = null, $month = null)
	{
		$this->month($year, $month);
	}

	function retrieveAll()
	{
		$xml = simplexml_load_file('http://www.comicvine.com/feeds/new-comics/');
		foreach ($xml->xpath('//item') as $item)
		{
			$title = explode('#', $item->title);
			$serie = SerieQuery::create()->findOneByTitle($title[0]);
			if ($serie == null)
			{
				$serie = new Serie();
				$serie->setTitle($title[0]);
				$serie->save();
			}
			$issue = IssueQuery::create()->filterBySerie($serie)->findOneByTitle($title[1]);
			if ($issue == null)
			{
				$issue = new Issue();
				$issue->setTitle($title[1]);
				$issue->setSerie($serie);
				$issue->setPubDate($item->pubDate);
				$issue->save();
			}
		}
	}

	function month($year = null, $month = null)
	{
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

		for ($i = $first['mday'] ; $i <= $last['mday'] ; $i++)
		{
			$dayNb = str_pad($i, 2, '0', STR_PAD_LEFT);
			$day = $this->getDay($year, $month, $dayNb);
			$calendar[$i]['day'] = $day;
			$issues = IssueQuery::create()->findByPubDate($year.'-'.$month.'-'.$dayNb);
			$calendar[$i]['issues'] = array();
			foreach ($issues as $issue)
			{
				$calendar[$i]['issues'][] = $issue->getSerie()->getTitle().' #'.$issue->getTitle();
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
