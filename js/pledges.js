var gridid1 = "#jqGridPledge";
var gridpagerid1 = "#jqGridPagerPledge";

var event_id ='';
var event_value =':[All];';
donator_id =':;';
donator_value =':[All];';
var d = (new Date()).getTime();

common.ajaxCall(false, "get", "json/donator.json?nocache="+d, null,
	function( response ) {
		donator_id += response['donator_id'][0];
		donator_value += response['donator_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

common.ajaxCall(false, "get", "json/event.json?nocache="+d, null,
	function( response ) {
		event_id += response['event_id'][0];
		event_value += response['event_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var pledgeColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Event', template:common.selectTemplate('event_id', 50, true, ' * ',
		'select', event_id, event_value, true,2,1,'')},
	{label: 'Donator', hidden:true, template:common.selectTemplate('donator_id', 50, false, ' * ',
		'select', donator_id, donator_value, true,3,1,'')},
	{label: 'Pledge Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
		template:common.numberTemplate('amount', 50, true, ' * ',4,1, {maxlength: 8})},
	{label: 'Payment Method', template:common.selectTemplate('payment_method_id', 50, true, ' * ',
		'select', payment_method_id, payment_method_value, true,5,1,'')},
	{label: 'Payment Type', template:common.selectTemplate('payment_type_id', 50, true, ' * ',
		'select', payment_type_id, payment_type_value, true,6,1,'')},
];

// activate the toolbar searching
//$(gridid1).jqGrid('filterToolbar',common.showFilterOptions);

function pushData1(result) {
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