var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var log_table_id =':;';
var log_table_value =':[All];';
var log_action_id =':;';
var log_action_value =':[All];';
common.ajaxCall(false, "get", "json/select.json", null,
	function( response ) {
		log_table_id += response['log_table_id'][0];
		log_table_value += response['log_table_value'][0];
		log_action_id += response['log_action_id'][0];
		log_action_value += response['log_action_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)
var eventColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Record ID', formatter:'integer', formatoptions:{},
		template:common.numberTemplate('record_id', 50, true, '',2,1, {})},
	{label: 'Table Name', template:common.selectTemplate('log_table', 50, true, '',
			'select', log_table_id, log_table_value,true,3,1,'')},
	{label: 'Action', template:common.selectTemplate('log_action', 50, true, '',
			'select', log_action_id, log_action_value,true,4,1,'')},
	{label: 'Log', template:common.textAreaTemplate('log', 100, false, '',true,5,1, '10', '40','')},
	{label: 'Log Date', template:common.dateTemplate('created_on', 100, true, '',6,1)},

];


function beforeShowForm(form) {
	$('#record_id',form).attr('disabled','true');
	$('#log_table',form).attr('disabled','true');
	$('#log_action',form).attr('disabled','true');
	$('#log',form).attr('disabled','true');
	$('#created_on',form).attr('disabled','true');
};

function onDblClickRow(rowid, ri, ci) {
	//alert(rowid + " - " + ri + " - " + ci)
    var p = $(this)[0].p;
    if (p.selrow !== rowid)
    	$(this).jqGrid('setSelection', rowid);
    $(this).jqGrid('viewGridRow', rowid, {width: 500});
}


$(gridid).jqGrid(common.gridOptions(gridpagerid, eventColModel, 'Log List',
	'logs.php', 900, null, null, 10, 230, onDblClickRow));

//$(gridid).jqGrid('navGrid', gridpagerid, {cloneToTop: true});

// activate the toolbar searching
$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	{search: false, view: false, add: false, edit: false, del: false, refresh: false},
	{},
	{},
	{},
	{},{width: 500}
);


fetchGridData();

function fetchGridData() {
	common.setGridData(gridid, "get", "logs.php", {action: 'loglist'}, pushData)
}

function pushData(result) {
	//debugger;
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			record_id: item.record_id,
			log_table: item.log_table,
			log_action: item.log_action,
			log: item.log,
			created_on: item.created_on,
		});
	}
	return arrayData;
}
