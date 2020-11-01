$(function () {
	$('#frm').submit(function (e) {
		e.preventDefault();
		const itemName = $('#itemName').val();

		if(!validator.isEmpty(itemName)) {
			$.ajax({
				url: "/InventorySystem/api/orders/request",
				method: "POST",
				headers: {
					'Authorization': 'Bearer ' + localStorage.getItem('token')
				},
				data: { name: itemName },
				dataType: "json"
			})
			.done((response) => {
				if(response.status) {
					alert('Add new request successfully!');
					$(this)[0].reset();
				} else {
					alert(response.error);
				}
			})
			.fail((error) => {
				alert('Some error occurred');
			})
		} else {
			alert("Please enter item name");
		}
	})
});