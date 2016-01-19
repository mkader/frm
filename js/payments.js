debugger;
console.log(donator_id);
var gridid2= "#jqGridPayment";
var gridpagerid2 = "#jqGridPagerPayment";
//alert(payment_method_id);
//alert(payment_method_value);
var paymentColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Pledge', template:common.selectTemplate('pledge_id', 50, false, '',
		'select', pledge_id, pledge_value, true,2,1,'')},
	{label: 'Donator', hidden:true,template:common.selectTemplate('donator_id', 50, false, ' * ',
		'select', donator_id, donator_value, true,3,1,'')},
	{label: 'Payment Date', template:common.dateTemplate('payment_date', 50, true, ' * ',4,1)},
	{label: 'Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
		template:common.numberTemplate('amount', 50, true, ' * ',5,1, {maxlength: 8})},
	{label: 'Payment Method', template:common.selectTemplate('payment_method_id', 50, true, ' * ',
			'select', payment_method_id, payment_method_value, true,6,1,'')},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ',true,9,1, '2', '23')},
];

// activate the toolbar searching
//$(gridid2).jqGrid('filterToolbar',common.showFilterOptions);

function pushData2(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			payment_date:item.payment_date,
			amount: item.amount,
			pledge_id: item.title,
			payment_method_id: item.payment_method,
			comments:item.comments
		});
	}
	return arrayData;
}