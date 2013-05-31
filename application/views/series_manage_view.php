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
			echo '<input type="checkbox" class="updateserie" value="'.$serie->getId().'" checked>';
		}
		else
		{
			echo '<input type="checkbox" class="updateserie" value="'.$serie->getId().'">';
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

<script type="text/javascript">
$('input.updateserie').click(function() {
	var id = this.value;
	var checked = this.checked;
	$(this).parents('div.serie').toggleClass("selected");
	var request = $.ajax({
		url: '<?php echo BASE_URL; ?>series/updateSerie/' + id + '/' + checked,
		type: "GET",
		dataType: "json"
	});
	/*request.done(function(data) {
		displayFeed(feedId, data.html, data.count, data.categorycount, data.valid);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});*/
})
</script>