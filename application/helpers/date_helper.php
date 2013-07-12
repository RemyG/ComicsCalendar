<?php

/**
 * 
 * @author remyg
 *
 */
class Date_helper {

	/**
	 *
	 * @param string $year
	 * @param string $month
	 * @return multitype:
	 */
	function firstOfMonth($year, $month)
	{
		return getdate(mktime(0, 0, 0, $month, 1, $year));
	}
	
	/**
	 *
	 * @param string $year
	 * @param string $month
	 * @return multitype:
	 */
	function lastOfMonth($year, $month)
	{
		return getdate(mktime(0, 0, 0, $month+1, 0, $year));
	}
	
	/**
	 *
	 *
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @return multitype:
	 */
	function getDay($year, $month, $day)
	{
		return getdate(mktime(0, 0, 0, $month, $day, $year));
	}
	
	/**
	 *
	 * @param string $year
	 * @param string $month
	 * @return string a formatted date string for the next month, as "Y-m" (2013-07)
	 */
	function getNextMonth($year, $month)
	{
		return date('Y-m', mktime(0, 0, 0, $month + 1, 1, $year));
	}
	
	/**
	 *
	 * @param string $year
	 * @param string $month
	 * @return string a formatted date string for the previous month, as "Y-m" (2013-07)
	 */
	function getPrevMonth($year, $month)
	{
		return date('Y-m', mktime(0, 0, 0, $month - 1, 1, $year));
	}

}

?>