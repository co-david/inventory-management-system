$(function () {
	let items = [];

	$(document).ready(function () {
		getOrders('all');
	})

	$("input[type='radio']").change(function () {
		getOrders($("input[type='radio']:checked").val());
	});

	$('body').on('click', '.closeOrder', function() {
		const orderId = parseInt($(this).attr('data-id'));

		$.ajax({
			url: "/InventorySystem/api/orders/close",
			method: "POST",
			headers: {
				'Authorization': 'Bearer ' + localStorage.getItem('token')
			},
			data: { id: orderId },
			dataType: "json"
		})
		.done((response) => {
			if(response.status) {
				const index = items.findIndex(item => item.id === orderId);
				items[index].status = 'done';
				renderTableData(items);
			} else {
				alert(response.error);
			}
		})
		.fail((error) => {
			alert('Some error occurred');
		})
	})

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
				`<td>${item.user}</td>` +
				`<td>${item.status}</td>` +
				`<td>${item.insertTime}</td>` +
				(item.status.toLowerCase() !== 'done' ? `<td><button data-id="${item.id}" class="closeOrder">Arrive</button></td>` : '<td></td>') +
				'</tr>'
		});

		$('#itemsTable tbody').html(`${dataRows}`);
	}
});