var gridid3= "#jqGridACH";
var gridpagerid3 = "#jqGridPagerACH";
//alert(payment_method_id);
//alert(payment_method_value);
var achColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Donator', hidden:true,template:common.selectTemplate('donator_id', 50, false, ' * ',
		'select', donator_id, donator_value, true,2,1,'')},
	{label: 'ACH Date', template:common.dateTemplate('ach_date', 50, true, ' * ',2,2)},
	{label: 'Bank Name', template:common.textTemplate('bank_name', 25, true, ' * ',true,4,1)},
	{label: 'Routing', template:common.textTemplate('routing_number', 25, true, ' * ',true,5,1)},
	{label: 'Account', template:common.textTemplate('account_number', 25, true, ' * ',true,5,2)},
	{label: 'Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
		template:common.numberTemplate('amount', 50, true, ' * ',6,1, {maxlength: 8})},
	{label: 'Cycle', template:common.textTemplate('cycle', 25, false, ' &nbsp; ',true,6,2)},
	{label: 'Start Date', template:common.dateTemplate('start_date', 50, true, ' * ',7,1)},
	{label: 'End Date', template:common.dateTemplate('end_date', 50, false, ' &nbsp; ',7,2)},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ',true,8,1, '2', '23')},
];

// activate the toolbar searching
//$(gridid3).jqGrid('filterToolbar',common.showFilterOptions);

function pushDataACH(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			ach_date:item.ach_date,
			bank_name: item.bank_name,
			routing_number: item.routing_number,
			account_number: item.account_number,
			void_check_included: item.void_check_included,
			cycle: item.cycle,
			amount: item.amount,
			start_date: item.start_date,
			end_date: item.end_date,
			comments:item.comments
		});
	}
	return arrayData;
}