var gridid1 = "#jqGridAttendance";
var gridpagerid1 = "#jqGridPagerAttendance";

teacher_id =':;';
teacher_value =':[All];';
var d = (new Date()).getTime();

common.ajaxCall(false, "get", "json/teacher.json?nocache="+d, null,
	function( response ) {
		teacher_id += response['teacher_id'][0];
		teacher_value += response['teacher_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
);

var attendanceColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Teacher', hidden:true, template:common.selectTemplate('teacher_id', 50, false, ' * ',
			'select', teacher_id, teacher_value, true,2,1,'')},
	{label: 'Attendance Date', template:common.dateTemplate('attendance_date', 50, true, ' * ',2,2)},
	{label: 'Time In',  template:common.textTemplate('time_in', 100, true, ' * ', true,3,1)},
   	{label: 'Time Out', template:common.textTemplate('time_out', 100, true, ' * ', true,3,2)},
	{label: 'Hours', template:common.numberTemplate('hours', 50, true, ' * ',3,3, {maxlength: 4})}
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
			teacher_id: item.student_teacher_id,
			attendance_date: item.attendance_date,
			time_in: item.time_in,
			time_out: item.time_out,
			hours: item.hours
		});
	}
	return arrayData;
}