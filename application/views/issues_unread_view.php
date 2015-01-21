<section class="unread-issues">
	<header><h1 class="title">Unread issues</h1></header>
	
	<!--
	{"issue_id":"64729","issue_number":"3","issue_title":"",
	"issue_cv_url":"http:\/\/www.comicvine.com\/all-new-captain-america-3\/4000-475927\/",
	"issue_date":"2015-01-14","serie_id":"8406","serie_title":"All-New Captain America",
	"serie_cv_url":"http:\/\/www.comicvine.com\/allnew-captain-america\/4050-78044\/"}
	-->
	
	<a href="#by-serie" id="order-by-serie">Order by serie</a>
	<a href="#by-date" id="order-by-date">Order by date</a>
	
	<section class="issues-by-date">
		<?php

		$currentDate = null;

		foreach ($issues as $issue) {

			if ($currentDate == null || $issue['issue_date'] != $currentDate) {
				echo '<div class="section-header">'.$issue['issue_date'].'</div>';
				$currentDate = $issue['issue_date'];
			}

			$description = $issue['serie_title'];

			if ($issue['issue_number'] != null && $issue['issue_number'] != '')
			{
				$description .= ' #'.$issue['issue_number'];
				if ($issue['issue_title'] != null && $issue['issue_title'] != '')
				{
					$description .= ' - '.$issue['issue_title'];
				}
			}
			else if ($issue['issue_title'] != null && $issue['issue_title'] != '')
			{
				$description .= " - ".$issue['issue_title'];
			}

			echo '<div class="issue">';
			echo '<input type="checkbox" id="chkbox_'.$issue['issue_id'].
				'" value="'.$issue['issue_id'].'" class="toggleissue">';
			echo '<label for="chkbox_'.$issue['issue_id'].'">'.$description.'</label>';
			echo '</div>';
		}
		?>
	</section>
	
	<section class="issues-by-serie">
	    
	</section>
</section>

<script type="text/javascript">
	
	$('a#order-by-date').hide();
	$('section.issues-by-serie').hide();
	
	$('a#order-by-date').click(function(e) {
		e.preventDefault();
		$('section.issues-by-serie').hide();
		$('section.issues-by-date').show();
		$('a#order-by-date').hide();
		$('a#order-by-serie').show();
	});
	
	$('a#order-by-serie').click(function(e) {
		
		e.preventDefault();		
		
		if ($('section.issues-by-serie').children().size() === 0) {
		
			$.getJSON( "/issues/unreadBySerie", function( data ) {
				var items = [];

				var currentSerie = null;

				$.each( data, function(key, item ) {

					if (currentSerie === null || currentSerie !== item.serie_id) {
						currentSerie = item.serie_id;
						var sectionLink = $('<a>' + item.serie_title + '</a>')
							.attr({ href: '<?php echo BASE_URL; ?>series/show/' + item.serie_id });
						var sectionHeader = $('<div></div>')
							.attr({ class: 'section-header' });
						sectionHeader.append(sectionLink);
						$('section.issues-by-serie').append(sectionHeader);
					}

					var description = '';

					if (item.issue_number !== null && item.issue_number !== '')
					{
						description += ' #' + item.issue_number;
						if (item.issue_title != null && item.issue_title != '')
						{
							description += ' - ' + item.issue_title;
						}
					}
					else if (item.issue_title != null && item.issue_title != '')
					{
						description += ' - ' + item.issue_title;
					}

					if (item.issue_date !== null && item.issue_date !== '') {
						description += " (" + item.issue_date + ")";
					}

					var serieDiv = $('<div></div>')
						.attr({ class: 'issue' });
					var serieInput = $('<input/>')
						.attr({ type: 'checkbox', 
							class: 'toggleserie', 
							value: item.issue_id,
							id: 'chkbox_' + item.issue_id});
					var serieLabel = $('<label>' + description + '</label>')
						.attr({ for: 'chkbox_' + item.issue_id });

					serieDiv.append(serieInput);
					serieDiv.append(serieLabel);

					$('section.issues-by-serie').append(serieDiv);
				});

				$('section.issues-by-date').hide();

			});
		}
		
		$('section.issues-by-date').hide();
		$('section.issues-by-serie').show();
		$('a#order-by-serie').hide();
		$('a#order-by-date').show();
		
	});
</script>