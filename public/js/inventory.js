$(function () {
	let items = [];

	$(document).ready(function () {
		getInventory('all');
	})

	$("input[type='radio']").change(function () {
		getInventory($("input[type='radio']:checked").val());
	});

	const getInventory = getBy => {
		$.ajax({
			url: "/InventorySystem/api/inventory/get?by=" +  getBy,
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
				`<td>${item.item}</td>` +
				`<td>${item.user}</td>` +
				`<td>${item.old}</td>` +
				`<td>${item.new}</td>` +
				`<td>${item.insertTime}</td>` +
				'</tr>>'
		});

		$('#itemsTable tbody').html(`${dataRows}`);
	}
});