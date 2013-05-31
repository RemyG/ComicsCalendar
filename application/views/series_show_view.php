<section class="show-serie">
	
	<?php
	
		echo '<header><h1 class="title">'.$serie->getTitle().'</h1></header>';

		if ($serie->getCvUrl() != null && $serie->getCvUrl() != '')
		{
			echo '<div class="cv-url"><a href="'.$serie->getCvUrl().'" target="_blank">View on ComicVine</a></div>';
		}

		echo '<section class="issues">';
		if (sizeof($issues) == 0)
		{
			echo '<header>No issue so far.</header>';
		}
		else if (sizeof($issues) == 1)
		{
			echo '<header>'.sizeof($issues).' issue so far.</header>';
		}
		else
		{
			echo '<header>'.sizeof($issues).' issues so far.</header>';
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

