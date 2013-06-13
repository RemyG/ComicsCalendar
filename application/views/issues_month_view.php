<section class="calendar">

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

<div class="month-calendar">

	<?php

		$today = date('Y-m-d');

		if ($firstWday > 1)
		{			
			for ($i = 1 ; $i < $firstWday ; $i++)
			{
				echo '<div class="day empty"></div>';
			}
		}
		foreach ($calendar as $dayNb => $day)
		{
			if ($today == $year.'-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-'.str_pad($dayNb, 2, "0", STR_PAD_LEFT))
			{
				echo '<div class="day today">';
			}
			else
			{
				echo '<div class="day">';
			}
			echo '
				<div class="date">
					<span class="day-number">'.$dayNb.'</span>
					<span class="weekday">'.$day['day']['weekday'].'</span>
				</div>
				<div class="issues">';
			foreach ($day['issues'] as $issue)
			{
				
				$found = false;
				if ($user != null)
				{
					foreach ($issue->getUsers() as $userIss)
					{
						if ($userIss->getId() == $user->getId())
						{
							$found = true;
							break;
						}
					}
				}
				echo '<div class="issue'.($found ? ' read' : '').'">';
				echo '<input type="checkbox" value="'.$issue->getId().'" class="updateissue" '.($found ? 'checked' : '').'>';
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

			echo '</div></div>';
		}

	?>

</div>

</section>

<script type="text/javascript">
$('input.updateissue').click(function() {
	var id = this.value;
	var checked = this.checked;
	$(this).parents('div.issue').toggleClass("read");
	var request = $.ajax({
		url: '<?php echo BASE_URL; ?>issues/toggleIssue/' + id + '/' + checked,
		type: "GET",
		dataType: "json"
	});
})
</script>