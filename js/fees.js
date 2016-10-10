var gridid2= "#jqGridFee";
var gridpagerid2 = "#jqGridPagerFee";

var event_id ='';
var event_value =':[All];';
var d = (new Date()).getTime();
common.ajaxCall(false, "get", "json/event.json?nocache="+d, null,
	function( response ) {
		event_id += response['event_id'][0];
		event_value += response['event_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var feeColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Enrollment', hidden:true,template:common.selectTemplate('enrollment_id', 50, false, '',
		'select', enrollment_id, enrollment_value, true,2,1,'')},
	{label: 'Event', hidden:false,template:common.selectTemplate('event_id', 50, false, ' * ',
		'select', event_id, event_value, true,3,1,'')},
	{label: 'Fee Date', template:common.dateTemplate('fee_date', 50, true, ' * ',4,1)},
	{label: 'Amount', template:common.numberTemplate('amount', 50, false, ' &nbsp; ',5,1, {maxlength: 4})},
	{label: 'Fee Method', template:common.selectTemplate('fee_method_id', 50, true, ' * ',
			'select', payment_method_id, payment_method_value, true,6,1,'')},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ',true,9,1, '2', '23')},
];

// activate the toolbar searching
//$(gridid2).jqGrid('filterToolbar',common.showFilterOptions);

function pushDataFee(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			fee_date:item.fee_date,
			amount: item.amount,
			//event_id: item.event_id,
			enrollment_id: item.enrollment_id,
			fee_method_id: item.fee_method,
			comments:item.comments,
			enrollment_name:item.parent_name,
			event_id:item.title
		});
	}
	return arrayData;
}