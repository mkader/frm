var gridid1 = "#jqGridPledge";
var gridpagerid1 = "#jqGridPagerPledge";

var payment_type_id =':;';
var payment_type_value =':[All];';
var payment_method_id =':;';
var payment_method_value =':[All];';
var event_id =':;';
var event_value =':[All];';
var donator_id =':;';
var donator_value =':[All];';
debugger;
common.ajaxCall(false, "get", "json/select.json", null,
	function( response ) {
		payment_type_id += response['payment_type_id'][0];
		payment_type_value += response['payment_type_value'][0];
		payment_method_id += response['payment_method_id'][0];
		payment_method_value += response['payment_method_value'][0];
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)
common.ajaxCall(false, "get", "json/donator.json", null,
	function( response ) {
		donator_id += response['donator_id'][0];
		donator_value += response['donator_value'][0];
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)
common.ajaxCall(false, "get", "json/event.json", null,
	function( response ) {
		event_id += response['event_id'][0];
		event_value += response['event_value'][0];
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)

var pledgeColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Event', template:common.selectTemplate('event_id', 50, true, ' * ',
			'select', event_id, event_value, true,2,1)},
	{label: 'Donator', template:common.selectTemplate('donator_id', 50, true, ' * ',
			'select', donator_id, donator_value, true,3,1)},
	{label: 'Pledge Amount', template:common.numberTemplate('amount', 50, true, ' * ',4,1)},
	{label: 'Payment Method', template:common.selectTemplate('payment_method_id', 50, true, ' * ',
			'select', payment_method_id, payment_method_value, true,5,1)},
	{label: 'Payment Type', template:common.selectTemplate('payment_type_id', 50, true, ' * ',
			'select', payment_type_id, payment_type_value, true,6,1)},
];

function afterSubmit1(response) {
	debugger;
	var res = common.decode(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		debugger;
		fetchGridData1();
		return [true];
	}
};

function editSettings() {
	return common.modalEdit('auto','',afterSubmit1);
}

$(gridid1).jqGrid(common.gridOptions(gridpagerid1, pledgeColModel, 'Pledge List', 'pledges.php',900));

// activate the toolbar searching
$(gridid1).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid1).navGrid(gridpagerid1,
	gridFooterIcons,
	editSettings(),
	common.modalCreate('auto', afterSubmit1),
	common.modalDelete(afterSubmit1)
);

fetchGridData1();

function fetchGridData1() {
	common.setGridData(gridid1, "get", "pledges.php", {action: 'pledgelist'}, pushData1)
}

function pushData1(result) {
	debugger;
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			//event_id:item.event_id,
			//donator_id:item.donator_id,
			amount: item.amount,
			//payment_method_id: item.payment_method_id,
			//payment_type_id: item.payment_type_id,
			event_id: item.title,
			donator_id: item.name,
			payment_method_id: item.payment_method,
			payment_type_id: item.payment_type
		});
	}
	return arrayData;
}