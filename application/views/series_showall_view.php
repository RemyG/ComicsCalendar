<section class="manage-series">

<?php

	$current = '';

	foreach ($series as $serie)
	{
		$firstChar = $serie->getTitle();
		$firstChar = $firstChar[0];
		if ($firstChar != $current)
		{
			if ($current != "")
			{
				echo '</div>';
			}
			echo '<div class="section-header">'.$firstChar.'</div>';
			echo '<div class="section">';
			$current = $firstChar;
		}
		echo '<div class="serie-container">';
		echo '<div class="serie">';
		echo '<a href="'.BASE_URL.'series/show/'.$serie->getId().'">'.$serie->getTitle().'</a>';
		echo '</div>';
		echo '</div>';
	}

	if ($current != "")
	{
		echo '</div>';
	}

?>

</section>