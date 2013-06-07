<section class="show-serie">
	
	<?php
	
		echo '<header><h1 class="title">'.$serie->getTitle().'</h1></header>';

		if ($serie->getCvUrl() != null && $serie->getCvUrl() != '')
		{
			echo '<div class="cv-url"><a href="'.$serie->getCvUrl().'" target="_blank">View on ComicVine</a></div>';
		}

		if (isset($user))
		{
			$found = false;
			foreach ($user->getSeries() as $tmpSerie)
			{
				if ($tmpSerie->getId() == $serie->getId())
				{
					$found = true;
					break;
				}
			}
			echo '<section class="follow-serie">';
			echo '<input type="checkbox" id="followthis" value="'.$serie->getId().'"'.
			($found ? ' checked ' : ' ').'name="followthis">
			<label for="followthis">Add to my series</label>';
			echo '</section>';
		}

		echo '<section class="issues">';
		if (sizeof($issues) == 0)
		{
			echo '<header>No issue so far.</header>';
		}
		else if (sizeof($issues) == 1)
		{
			echo '<header>'.sizeof($issues).' issue so far:</header>';
		}
		else
		{
			echo '<header>'.sizeof($issues).' issues so far:</header>';
		}
		foreach($issues as $issue)
		{
			echo '<div class="issue">';
			if ($issue->getIssueNumber() != null && $issue->getIssueNumber() != '')
			{
				echo '#'.$issue->getIssueNumber();
				if ($issue->getTitle() != null && $issue->getTitle() != '')
				{
					echo ' - '.$issue->getTitle();
				}
			}
			else if ($issue->getTitle() != null && $issue->getTitle() != '')
			{
				echo $issue->getTitle();
			}
			echo ', published on '.$issue->getPubDate('Y/m/d');
			echo '</div>';
		}
		echo '</section>';

	?>

</section>

<script type="text/javascript">
$('#followthis').click(function() {
	var id = this.value;
	var checked = this.checked;
	var request = $.ajax({
		url: '<?php echo BASE_URL; ?>series/updateSerie/' + id + '/' + checked,
		type: "GET",
		dataType: "json"
	});
})
</script>