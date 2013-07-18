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
</script>
