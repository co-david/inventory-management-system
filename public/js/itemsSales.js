$(function () {
	let items = [];

	$(document).ready(function() {
		$.ajax({
			url: "/InventorySystem/api/items/get",
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
	})

	$('body').on('click', '.updateInventory', function () {
		const id = $(this).attr('data-id');
		const inventory = $(this).prev().val();

		if(validator.isInt(id) && validator.isInt(inventory))
		{
			$.ajax({
				url: "/InventorySystem/api/items/updateInventory",
				method: "POST",
				headers: {
					'Authorization': 'Bearer ' + localStorage.getItem('token')
				},
				data: { id, inventory },
				dataType: "json"
			})
				.done((response) => {
					if(response.status) {
						const index = items.findIndex(item => item.id === parseInt(id));
						items[index].inventory = inventory;
						renderTableData(items);
					} else {
						alert(response.error);
					}
				})
				.fail((error) => {
					alert('Some error occurred');
				})
		}
		else
		{
			alert('Invalid value');
		}
	})

	$('body').on('click', '.report', function() {
		const itemId = parseInt($(this).attr('data-id'));

		$.ajax({
			url: "/InventorySystem/api/items/setMissing",
			method: "POST",
			headers: {
				'Authorization': 'Bearer ' + localStorage.getItem('token')
			},
			data: { id: itemId },
			dataType: "json"
		})
		.done((response) => {
			if(response.status) {
				const index = items.findIndex(item => item.id === itemId);
				items[index].isMissing = 'Yes';
				renderTableData(items);
			} else {
				alert(response.error);
			}
		})
		.fail((error) => {
			alert('Some error occurred');
		})
	})

	$('#searchInput').keyup(function() {
		const q = $(this).val();
		if(q) {
			renderTableData(findItems(items, q));
		} else {
			renderTableData(items);
		}
	})

	const renderTableData = (items) => {
		const dataRows = items.map(item => {
			return `<tr class="is-missing-${item.isMissing.toLowerCase()}">` +
				`<td>${item.name}</td>` +
				`<td>${item.inventory}</td>` +
				`<td>${item.isMissing}</td>` +
				`<td>${item.lastUpdate}</td>` +
				`<td><div class="update-inventory-action"><input value="${item.inventory}" class="inventoryInput" type="number" min="0" /><button data-id="${item.id}" class="updateInventory">Update Inventory</button></div><div class="missing-action"><button data-id="${item.id}" class="report">Mark as missing</button></div></td>` +
				'</tr>'
		});

		$('#itemsTable tbody').html(`${dataRows}`);
	}

	const findItems = (items, q) => {
		return items.filter((item, index) => item.name.toLocaleLowerCase().includes(q.toLowerCase()))
	}
});