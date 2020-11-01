$(function () {
	$('#loginFrm').submit((e) => {
		e.preventDefault();

		const email = $('#email').val();
		const password = $('#password').val();
		let status = true;

		$('.error-txt, .general-error').html('');

		if(!validator.isEmail(email)) {
			status = false;
			$('#email').next().html('Please enter valid email');
		}

		if(!validator.isLength(password, { min: 6 })) {
			status = false;
			$('#password').next().html('Please enter at list 6 chars');
		}

		if(status) {
			$.ajax({
				url: "/InventorySystem/api/users/login",
				method: "POST",
				data: { email, password },
				dataType: "json"
			})
			.done((response) => {
				if(response.status) {
					localStorage.setItem('token', response.data.token);
					switch (response.data.type) {
						case 'sales':
							window.location.href = '/InventorySystem/sales/items';
							break;
						case 'logistic':
							window.location.href = '/InventorySystem/logistic/items';
							break;
						default:
							$('#generalError').html('Invalid user type');
							break;
					}
				} else {
					$('#generalError').html(response.error);
				}
			})
			.fail(() => {
				$('#generalError').html('Some error occurred');
			})
		}
	});
});