var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";

var memberColModel = [
	{label: 'id', template:common.idTemplate('id',5,1)},
	{label: 'Name', template:common.textTemplate('name', 100, true, ' * ',true,1,1)},
	{label: 'E-mail', formatter:'email', template:common.textTemplate('email', 100, true, ' * ',true,2,1)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, true, ' * ',true,3,1)},
	{label: 'Active', template:common.selectTemplate('active', 50, true, ' &nbsp; ',
		'checkbox', active_id, active_value, true,4,1)}
];

function editSettings() {
	return $.extend(common.modalEdit('auto', "",
		common.afterSubmit, null), {
		beforeSubmit: function(postdata, formid) {
			return[common.validateEmail(postdata.email),'E-mail: Field is not valid'];
		},
	});
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, memberColModel, 'Member List',
	'members.php', 900, null, null, 10, 230, common.ondblClickRow));

$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	editSettings(),
	$.extend( common.modalCreate('auto',common.afterSubmit, null), {
		beforeSubmit: function(postdata, formid) {
			return[common.validateEmail(postdata.email),'E-mail: Field is not valid'];
		},
	}),
	common.modalDelete(common.afterSubmit)
);

fetchGridData();


function fetchGridData() {
	common.setGridData(gridid,"get", "members.php", {action: 'memberlist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			name: item.name,
			email: item.email,
			phone: item.phone,
			active: item.active
		});
	}
	return arrayData;
}

