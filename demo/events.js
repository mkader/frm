var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var pledge_type_id =':;';
var pledge_type_value =':[All];';
var d = (new Date()).getTime();
common.ajaxCall(false, "get", "json/select.json?nocache="+d, null,
	function( response ) {
		pledge_type_id += response['pledge_type_id'][0];
		pledge_type_value += response['pledge_type_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)
var eventColModel = [
	{label: 'id', template:common.idTemplate('id',7,1)},
	{label: 'Title', template:common.textTemplate('title', 100, true, ' * ',true,1,1)},
	{label: 'Event Date', template:common.dateTemplate('event_date', 50, true, ' * ',2,1)},
	{label: 'Target Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 0, prefix: "$ "},
		template:common.numberTemplate('target_amount', 50, true, ' * ',3,1, {maxlength: 8})},
	{label: 'Pledged Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
	    hidden: false, editable: false, editrules: { edithidden: false }, hidedlg: true,
		template:common.numberTemplate('pledged_amount', 50, false, '  ',5,1, {maxlength: 8})},
	{label: 'Paid', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
	    hidden: false, editable: false, editrules: { edithidden: false }, hidedlg: true,
		template:common.numberTemplate('pledged_paid', 50, false, '  ',5,1, {maxlength: 8})},
	{label: 'Percentage', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: ""},
	    hidden: false, editable: false, editrules: { edithidden: false }, hidedlg: true,
		template:common.numberTemplate('pledged_percentage', 50, false, '  ',5,1, {maxlength: 8})},
	{label: 'Event Type', template:common.selectTemplate('pledge_type', 50, true, ' * ',
			'select', pledge_type_id, pledge_type_value,true,4,1,'')},
	{label: 'Location', template:common.textTemplate('location', 150, true, ' * ',true,5,1)},
	{label: 'Active', template:common.selectTemplate('active', 50, true, ' &nbsp; ',
			'checkbox', active_id, active_value, true,6,1,'')},
	{label: 'Description', template:common.textAreaTemplate('description', 100, false, ' &nbsp; ',true,8,1, '2', '23')},
];

function editSettings() {
	return $.extend(common.modalEdit('auto','',common.afterSubmit, beforeShowForm));
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, eventColModel, 'Event List',
	'events.php', 900, null, null, 10, 230, common.onDblClickRow));

//$(gridid).jqGrid('navGrid', gridpagerid, {cloneToTop: true});

// activate the toolbar searching
$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	common.modalEdit('auto','',common.afterSubmit, beforeShowForm),
	common.modalCreate('auto',common.afterSubmit, beforeShowForm),
	common.modalDelete(common.afterSubmit)
);

function beforeShowForm(form) {
	common.numberOnly('#target_amount');
};

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
			pledged_amount: item.pledged_amount,
			pledged_paid: item.pledged_paid,
			pledged_percentage:item.pledged_percentage,
			pledge_type: item.pledge_type,
			active: item.active
		});
	}
	return arrayData;
}
