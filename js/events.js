var eventColModel = [
	{label: 'id', template:common.idTemplate('id')},
	{label: 'Title', template:common.textTemplate('title', 100, true, ' * ')},
	{label: 'Event Date', template:common.dateTemplate('event_date', 50, true, ' * ')},
	{label: 'Target Amount', template:common.numberTemplate('target_amount', 50, true, ' * ')},
	{label: 'Event Type', template:common.selectTemplate('pledge_type', 50, true, ' * ',
			'select', ':;1:Operation;2:New Masjid', ':[All];Operation:Operation;New Masjid:New Masjid')},
	{label: 'Location', template:common.textTemplate('location', 150, true, ' * ')},
	{label: 'Description', template:common.textAreaTemplate('description', 100, false, " &nbsp; ")},
];

$("#jqGrid").jqGrid(common.gridOptions(eventColModel, 'Event List', 'events.php'));

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
	common.setGridData("get", "events.php", {action: 'eventlist'}, pushData) 
}

function pushData(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			title: item.title,
			name: item.name,
			event_date: item.event_date,
			location: item.location,
			description: item.description,
			target_amount: item.target_amount,
			pledge_type: item.pledge_type
		});
	}
	return arrayData;
}
