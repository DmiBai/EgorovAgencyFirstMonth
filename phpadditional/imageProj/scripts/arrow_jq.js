$(document).ready(function () {
	$(document).on('submit', '#angleForm', function(event){
		event.preventDefault();
		if($('#angle').val() !== '') {
			$.ajax({
				url: './handlers/arrow.php',
				type: 'POST',
				dataType: 'html',
				data: {angle: $('#angle').val()},
				success: function (response) {
					console.log(response);
					$('img').attr('src', '');
					$('img').attr('src', 'img/arrow.png');
				},
				error: function (response) {
				}
			});
		}
	})
});
