/*
  * PHP_auth_register (https://github.com/chandrakant11/php_auth_register)
*/

'use strick';
	
$(function(){

	$('.form').on('submit', function(event){
		event.preventDefault();

		$form = $(this);
		submitFunction($form);
	});

	$('#forgot-link').click(function(event){
		$('#login-modal').modal('hide');
	});
});

function submitFunction($form) {

	$footer = $form.parent('.modal-body').next('.modal-footer');
	$footer.html('<div class="spinner-border" role="status"><span class="sr-only"></span></div>');

	$.ajax({
		url: $form.attr('action'),
		method: $form.attr('method'),
		data: $form.serialize(),
		success: function(response){
			response = JSON.parse(response);

			if(response.success) {

				setTimeout(function(){
					$footer.html(response.msg);
				}, 1000);
				setTimeout(function(){
					window.location = response.url;
				}, 3000);
			} else if(response.error) {
				$footer.html(response.msg);
			}
		}
	});
}