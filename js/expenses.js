var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var event_id =':;';
var event_value =':[All];';

common.ajaxCall(false, "get", "json/event.json", null,
	function( response ) {
		event_id += response['event_id'][0];
		event_value += response['event_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var eventColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Event', template:common.selectTemplate('event_id', 50, true, ' * ',
			'select', event_id, event_value, true,2,1)},
	{label: 'Expense Date', template:common.dateTemplate('expense_date', 50, true, ' * ',3,1)},
	{label: 'Title', template:common.textTemplate('title', 100, true, ' * ',true,4,1)},
	{label: 'Amount', formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 0, prefix: "$ "},
		template:common.numberTemplate('amount', 50, true, ' * ',5,1, {maxlength: 8})},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ',true,6,1, '2', '23')},
];

/*function editSettings() {
	return common.modalEdit('auto','',common.afterSubmit);
}*/

$(gridid).jqGrid(common.gridOptions(gridpagerid, eventColModel, 'Expense List',
	'expenses.php', 900, null, null, 10, 230, common.onDblClickRow));

//$(gridid).jqGrid('navGrid', gridpagerid, {cloneToTop: true});

// activate the toolbar searching
$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

function beforeShowForm(form) {
	common.decimalOnly('#amount');
};

function afterShowForm(form) {
	common.autoComplete('#title', 'json/title.json', 'jsonp');
};



$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	common.modalEdit('auto','',common.afterSubmit, beforeShowForm),
	$.extend( common.modalCreate('auto',common.afterSubmit, beforeShowForm), {afterShowForm:afterShowForm}),
	common.modalDelete(common.afterSubmit)
);

fetchGridData();

function fetchGridData() {
	common.setGridData(gridid, "get", "expenses.php", {action: 'expenselist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			title: item.title,
			event_id: item.event_title,
			expense_date: item.expense_date,
			comments: item.comments,
			amount: item.amount
		});
	}
	return arrayData;
}

