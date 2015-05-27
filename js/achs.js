var gridid3= "#jqGridACH";
var gridpagerid3 = "#jqGridPagerACH";
//alert(payment_method_id);
//alert(payment_method_value);
var achColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Donator', hidden:true,template:common.selectTemplate('donator_id', 50, false, ' * ',
		'select', donator_id, donator_value, true,2,1,'')},
	{label: 'ACH Date', template:common.dateTemplate('ach_date', 50, true, ' * ',3,1)},
	{label: 'Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
		template:common.numberTemplate('amount', 50, true, ' * ',4,1, {maxlength: 8})},
	{label: 'Payment Method', template:common.selectTemplate('payment_method_id', 50, true, ' * ',
			'select', payment_method_id, payment_method_value, true,5,1, '')},
	{label: 'Cycle', template:common.textTemplate('cycle', 25, true, ' * ',true,6,1)},
	{label: 'Start Date', template:common.dateTemplate('start_date', 50, true, ' * ',7,1)},
	{label: 'End Date', template:common.dateTemplate('end_date', 50, true, ' * ',8,1)},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ',true,9,1, '2', '23')},
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
			payment_method_id: item.payment_method,
			ach_date:item.ach_date,
			bank_name: item.bank_name,
			bank_account_type_id: item.bank_account_type_id,
			routing_number: item.routing_number,
			account_number: item.account_number,
			void_check_included: item.void_check_included,
			credit_card_type_id: item.credit_card_type_id,
			credit_card_number: item.credit_card_number,
			credit_card_expiraiton_date: item.credit_card_expiraiton_date,
			credit_card_security_code: item.credit_card_security_code,
			cycle: item.cycle,
			amount: item.amount,
			start_date: item.start_date,
			end_date: item.end_date,
			comments:item.comments
		});
	}
	return arrayData;
}