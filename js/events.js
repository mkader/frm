var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var pledge_type_id =':;';
var pledge_type_value =':[All];';
common.ajaxCall(false, "get", "json/select.json", null,
	function( response ) {
		pledge_type_id += response['pledge_type_id'][0];
		pledge_type_value += response['pledge_type_value'][0];
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)
var eventColModel = [
	{label: 'id', template:common.idTemplate('id',7,1)},
	{label: 'Title', template:common.textTemplate('title', 100, true, ' * ',true,1,1)},
	{label: 'Event Date', template:common.dateTemplate('event_date', 50, true, ' * ',2,1)},
	{label: 'Target Amount', template:common.numberTemplate('target_amount', 50, true, ' * ',3,1)},
	{label: 'Event Type', template:common.selectTemplate('pledge_type', 50, true, ' * ',
			'select', pledge_type_id, pledge_type_value,true,4,1)},
	{label: 'Location', template:common.textTemplate('location', 150, true, ' * ',true,5,1)},
	{label: 'Description', template:common.textAreaTemplate('description', 100, false, ' &nbsp; ',true,6,1)},
];

function editSettings() {
	return common.modalEdit('auto','',common.afterSubmit);
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, eventColModel, 'Event List', 'events.php', 900));

// activate the toolbar searching
$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	editSettings(),
	common.modalCreate('auto',common.afterSubmit),
	common.modalDelete(common.afterSubmit)
);

fetchGridData();

function fetchGridData() {
	common.setGridData(gridid, "get", "events.php", {action: 'eventlist'}, pushData)
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
