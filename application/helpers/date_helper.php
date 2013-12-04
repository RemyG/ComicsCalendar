<?php

/**
 * Helper class to manipulate dates
 * 
 * @author RemyG
 */
class Date_helper {

	/**
	 * Return the date/time information of the first day of the month.
	 * 
	 * @param string $year
	 * @param string $month
	 * @return array an associative array of information related to the timestamp.
	 */
	function firstOfMonth($year, $month)
	{
		return getdate(mktime(0, 0, 0, $month, 1, $year));
	}
	
	/**
	 * Return the date/time information of the last day of the month.
	 * 
	 * @param string $year
	 * @param string $month
	 * @return array an associative array of information related to the timestamp.
	 */
	function lastOfMonth($year, $month)
	{
		return getdate(mktime(0, 0, 0, $month+1, 0, $year));
	}
	
	/**
	 * Return the date/time information of the specified date.
	 *
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @return array an associative array of information related to the timestamp.
	 */
	function getDay($year, $month, $day)
	{
		return getdate(mktime(0, 0, 0, $month, $day, $year));
	}
	
	/**
	 * Return a formatted date string for the next month, as "Y-m" (eg. "2013-07")
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
	 * Return a formatted date string for the previous month, as "Y-m" (eg. "2013-07")
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