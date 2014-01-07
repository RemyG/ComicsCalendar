<script type="text/javascript">
	$('input.toggleserie').click(function() {
		var id = this.value;
		var checked = this.checked;
		$(this).parents('div.serie').toggleClass("selected");
		var request = $.ajax({
			url: '<?php echo BASE_URL; ?>series/toggleSerie/' + id + '/' + checked,
			type: "GET",
			dataType: "json"
		});
	})
	$('input.toggleissue').click(function() {
		var id = this.value;
		var checked = this.checked;
		$(this).parents('div.issue').toggleClass("selected");
		var request = $.ajax({
			url: '<?php echo BASE_URL; ?>issues/toggleIssue/' + id + '/' + checked,
			type: "GET",
			dataType: "json"
		});
	})
	
	$('#followthis').click(function() {
		var id = this.value;
		var checked = this.checked;
		var request = $.ajax({
			url: '<?php echo BASE_URL; ?>series/toggleSerie/' + id + '/' + checked,
			type: "GET",
			dataType: "json"
		});
	})
	
	$('#serie-import').click(function(e) {
		e.preventDefault();
		$('#loading').css({
		    height: $('#loading').parent().height(), 
		    width: $('#loading').parent().width()
		});
		$('#loading').show();
		var id = $('#serie-id').val();
		var request = $.ajax({
			url: '<?php echo BASE_URL; ?>issues/importSerie/' + id,
			type: "GET",
			dataType: "json"
		}).done(function(data) {
			$('#serie-title').html(data.serietitle);
			$('#issues-nb').html(data.issuesnb);
			$('#loading').hide();
			$( "#dialog-message" ).dialog({
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		});
	})
</script>
