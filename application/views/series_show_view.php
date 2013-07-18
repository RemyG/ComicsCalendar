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
			$description = '';
			if ($issue->getIssueNumber() != null && $issue->getIssueNumber() != '')
			{
				$description .= '#'.$issue->getIssueNumber();
				if ($issue->getTitle() != null && $issue->getTitle() != '')
				{
					$description .= ' - '.$issue->getTitle();
				}
			}
			else if ($issue->getTitle() != null && $issue->getTitle() != '')
			{
				$description .= $issue->getTitle();
			}
			$description .= ', published on '.$issue->getPubDate('Y/m/d');
			
			$found = false;
			foreach ($userIssues as $issueTmp)
			{
				if ($issueTmp->getId() == $issue->getId())
				{
					$found = true;
					break;
				}
			}
			if (isset($user))
			{
				echo '<div class="issue'.($found ? ' selected' : '').'">';
				echo '<input type="checkbox" id="chkbox_'.$issue->getId().'" value="'.$issue->getId().'" class="toggleissue" '.($found ? 'checked' : '').' >';
				echo '<label for="chkbox_'.$issue->getId().'">'.$description.'</label>';
				echo '</div>';
			}
			else
			{
				echo '<div class="issue">';
				echo $description;
				echo '</div>';
			}
			
		}
		echo '</section>';

	?>

</section>