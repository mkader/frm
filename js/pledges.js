var pledgeColModel = [
	{label: 'id', template:common.idTemplate('id')},
	{label: 'Event', template:common.selectTemplate('event_id', 50, true, ' * ',
			'select', ':;1:Operation;2:New Masjid', ':[All];Operation:Operation;New Masjid:New Masjid')},
	{label: 'Donator', template:common.selectTemplate('donator_id', 50, true, ' * ',
			'select', ':;1:Operation;2:New Masjid', ':[All];Operation:Operation;New Masjid:New Masjid')},
	{label: 'Pledge Amount', template:common.numberTemplate('amount', 50, true, ' * ')},
	{label: 'Payment Method', template:common.selectTemplate('payment_method_id', 50, true, ' * ',
			'select', ':;1:Operation;2:New Masjid', ':[All];Operation:Operation;New Masjid:New Masjid')},
	{label: 'Payment Type', template:common.selectTemplate('payment_type_id', 50, true, ' * ',
			'select', ':;1:Operation;2:New Masjid', ':[All];Operation:Operation;New Masjid:New Masjid')},
];

$("#jqGrid").jqGrid(common.gridOptions(pledgeColModel, 'Pledge List', 'pledges.php'));

// activate the toolbar searching
$('#jqGrid').jqGrid('filterToolbar',common.showFilterOptions);

$("#jqGrid").navGrid("#jqGridPager",
	gridFooterIcons,
	common.modalEdit('auto',''),
	common.modalCreate('auto'),
	common.modalDelete()
);

fetchGridData();

function fetchGridData() {
	common.setGridData("get", "pledges.php", {action: 'pledgelist'}, pushData) 
}

function pushData(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			event: item.event,
			name: item.name,
			donator: item.donator,
			amount: item.amount,
			payment_method: item.payment_method,
			payment_type: item.payment_type
		});
	}
	return arrayData;
}

