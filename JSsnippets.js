//default value acting as placeholder

$(function(){
	$('#contact form.contact_form input, #contact form.contact_form textarea').click(function(){
		var value = $(this).val();
		if(value == 'Your First Name' || value == 'Your Last Name' || value=='Your Email Address' || value=='Message'){
			$(this).val('');
		}
		$(this).focusout(function(){
			var check = $(this).val();
			if(check == ''){
				$(this).val(value);
			}
		});
	});
});