<?php if (isset($newSeries) && $newSeries != null && sizeof($newSeries) > 0) { ?>

	<section class="new-series">
	
		<div class="section-header">New series added since your last visit</div>
	
		<div class="section">
	
	<?php
	
		foreach ($newSeries as $serie)
		{
			
			echo '<div class="serie-container">';
			echo '<div class="serie">';
			echo '<input type="checkbox" class="toggleserie" value="'.$serie->getId().'">';
			echo '<a href="'.BASE_URL.'series/show/'.$serie->getId().'">'.$serie->getTitle().'</a>';
			echo '</div>';
			echo '</div>';
		}
	
	?>
	
		</div>
	
	</section>

<?php } ?>

<section class="manage-series">

<?php

	$current = '';

	foreach ($series as $serie)
	{
		$firstChar = $serie->getTitle();
		$firstChar = strtoupper($firstChar[0]);
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
		$found = false;
		foreach ($user->getSeries() as $userSerie)
		{
			if ($serie->getId() == $userSerie->getId())
			{
				$found = true;
				break;
			}
		}
		echo '<div class="serie-container">';
		echo '<div class="serie'.($found ? ' selected' : '').'">';
		if ($found)
		{
			echo '<input type="checkbox" class="toggleserie" value="'.$serie->getId().'" checked>';
		}
		else
		{
			echo '<input type="checkbox" class="toggleserie" value="'.$serie->getId().'">';
		}
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