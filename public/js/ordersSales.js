$(function () {
	let items = [];

	$(document).ready(function () {
		getOrders('all');
	})

	$("input[type='radio']").change(function () {
		getOrders($("input[type='radio']:checked").val());
	});

	const getOrders = getBy => {
		$.ajax({
			url: "/InventorySystem/api/orders/get?by=" +  getBy,
			method: "GET",
			headers: {
				'Authorization': 'Bearer ' + localStorage.getItem('token')
			},
			dataType: "json"
		})
			.done((response) => {
				if(response.status) {
					items = response.data;
					renderTableData(items);
				} else {
					alert(response.error);
				}
			})
			.fail((error) => {
				alert('Some error occurred');
			})
	}

	const renderTableData = (items) => {
		const dataRows = items.map(item => {
			return '<tr>' +
				`<td>${item.name}</td>` +
				`<td>${item.status}</td>` +
				`<td>${item.insertTime}</td>` +
				'</tr>>'
		});

		$('#itemsTable tbody').html(`${dataRows}`);
	}
});