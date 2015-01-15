<section class="pagination">
	<?php
	
		
		$nums = range(0, 9);
		$alphas = range('A', 'Z');
		
		echo '<span class="pagination-alpha"><a href="#%" data-alpha="-">%</a></span>';
		
		foreach ($nums as $num) {
			echo '<span class="pagination-alpha"><a href="#'.$num.'" data-alpha="'.$num.'">'.$num.'</a></span>';
		}
		
		foreach ($alphas as $alpha) {
			echo '<span class="pagination-alpha"><a href="#'.$alpha.'" data-alpha="'.$alpha.'">'.$alpha.'</a></span>';
		}
	?>
</section>

<script type="text/javascript">
	$('.pagination-alpha a').click(function(e) {
		e.preventDefault();
		
		$('.pagination-alpha a').removeClass('selected');
		$(this).addClass('selected');
		
		var letter = $(this).attr('data-alpha');
		
		$.getJSON( "/series/managebyletter/" + letter, function( data ) {
			var items = [];
			$('section.new-series').remove();
			$('section.manage-series').html('');
			
			var sectionHeader = $('<div>' + letter + '</div>')
				.attr({ class: 'section-header' });
			
			$('section.manage-series').append(sectionHeader);
			
			var sectionSeries = $('<div></div>')
				.attr({ class: 'section' });
			
			$.each( data, function(key, item ) {
				var serieLink = $('<a>' + item.title + '</a>')
					.attr({ href: "<?php echo BASE_URL; ?>series/show/" + item.id});
				var serieInput = $('<input/>')
					.attr({ type: 'checkbox', class: 'toggleserie', value: item.id });
				if (item.user_id != null) {
					serieInput.attr({ checked: 'checked' });
				}
				var serieDiv = $('<div></div>')
					.attr({ class: 'serie' });
				if (item.user_id != null) {
					serieDiv.addClass('selected');
				}
				serieDiv.append(serieInput);
				serieDiv.append(serieLink);
				var serieContainer = $('<div></div>')
					.attr({ class: 'serie-container' });
				serieContainer.append(serieDiv);
				sectionSeries.append(serieContainer);
			});
			
			$('section.manage-series').append(sectionSeries);
		});
		
	});
</script>

<?php if (isset($newSeries) && $newSeries != null && sizeof($newSeries) > 0) { ?>

	<section class="new-series">
	
		<div class="section-header">New series added since your last visit</div>
	
		<div class="section">
	
	<?php
	
		foreach ($newSeries as $serie) {
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

</section>