<div class="calendar-navigation">
<?php
	$prev = explode("-", $prevMonth);	
	echo '
		<div class="prev">
			<a href="'.BASE_URL.'issues/month/'.$prev[0].'/'.$prev[1].'">
				<i class="icon-chevron-sign-left"> </i> '.$prev[1].'/'.$prev[0].'
			</a>
		</div>';
	echo '<div class="current">'.$month.'/'.$year.'</div>';
	$next = explode("-", $nextMonth);
	echo '
		<div class="next">
			<a href="'.BASE_URL.'issues/month/'.$next[0].'/'.$next[1].'">
				'.$next[1].'/'.$next[0].' <i class="icon-chevron-sign-right"> </i>
			</a>
		</div>';
?>
</div>

<table class="month-calendar">

	<?php

		$today = date('Y-m-d');

		echo '<tr>';
		for ($i = 1 ; $i < $firstWday ; $i++)
		{
			echo '<td class="empty"></td>';
		}
		foreach ($calendar as $dayNb => $day)
		{
			if ($today == $year.'-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-'.str_pad($dayNb, 2, "0", STR_PAD_LEFT))
			{
				echo '<td class="today">';
			}
			else
			{
				echo '<td>';
			}
			echo '
				<div class="date">
					<span class="day-number">'.$dayNb.'</span>
					<span class="weekday">'.$day['day']['weekday'].'</span>
				</div>';
			foreach ($day['issues'] as $issue)
			{
				echo '<div class="issue">';
				/*if ($issue->getSerie()->getCvUrl() != null && $issue->getSerie()->getCvUrl() != '')
				{
					echo '<a href="'.$issue->getSerie()->getCvUrl().'" target="_blank">';
					echo $issue->getSerie()->getTitle();
					echo '</a>';
				}
				else
				{
					echo $issue->getSerie()->getTitle();
				}*/
				echo '<a href="'.BASE_URL.'series/show/'.$issue->getSerie()->getId().'">'.$issue->getSerie()->getTitle().'</a>';
				if ($issue->getIssueNumber() != null && $issue->getIssueNumber() != '')
				{
					echo ' #'.$issue->getIssueNumber();
				}
				if ($issue->getTitle() != null && $issue->getTitle() != '')
				{
					echo ' - '.$issue->getTitle();
				}
				echo '</div>';
			}
			echo '</td>';
			if ($day['day']['wday'] == 0)
			{
				echo '</tr><tr>';
			}

		}
		echo '</tr>';

	?>

</table>