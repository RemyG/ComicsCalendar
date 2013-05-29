<ul>

<?php

	foreach ($series as $serie)
	{
		echo '<li>'.$serie->getTitle().'</li>';
	}

?>

</ul>