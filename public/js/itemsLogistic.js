$(function () {
	let items = [];

	$(document).ready(function() {
		getItems('missing');
	})

	$("input[type='radio']").change(function () {
		getItems($("input[type='radio']:checked").val());
	});

	$('#searchInput').keyup(function() {
		const q = $(this).val();
		if(q) {
			renderTableData(findItems(items, q));
		} else {
			renderTableData(items);
		}
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

	const getItems = getBy => {
		$.ajax({
			url: "/InventorySystem/api/items/get?by=" + getBy,
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
			return `<tr class="is-missing-${item.isMissing.toLowerCase()}">` +
				`<td>${item.name}</td>` +
				`<td>${item.inventory}</td>` +
				`<td>${item.isMissing}</td>` +
				`<td>${item.lastUpdate}</td>` +
				`<td><input value="${item.inventory}" class="inventoryInput" type="number" min="0" /><button data-id="${item.id}" class="updateInventory">Update Inventory</button></td>`
				'</tr>'
		});

		$('#itemsTable tbody').html(`${dataRows}`);
	}

	const findItems = (items, q) => {
		return items.filter((item, index) => item.name.toLocaleLowerCase().includes(q.toLowerCase()))
	}
});