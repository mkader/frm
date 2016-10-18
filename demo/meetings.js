var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";

var meetingColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Date', template:common.dateTemplate('meeting_date', 50, true, ' * ',2,1)},
	{label: 'Time', template:common.textTemplate('meeting_time', 100, true, ' * ',true,3,1)},
];

function editSettings() {
	return $.extend(common.modalEdit('auto', "", common.afterSubmit, null));
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, meetingColModel, 'Meeting List',
	'meetings.php', 900, null, null, 10, 230, common.onDblClickRow));

// activate the toolbar searching
//$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	common.modalEdit('auto','',common.afterSubmit, null),
	common.modalCreate('auto',common.afterSubmit, null),
	common.modalDelete(common.afterSubmit)
);

fetchGridData();

function fetchGridData() {
	common.setGridData(gridid, "get", "meetings.php", {action: 'meetinglist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			meeting_time: item.meeting_time,
			meeting_date: item.meeting_date,
		});
	}
	return arrayData;
}

