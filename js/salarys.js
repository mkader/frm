var gridid2 = "#jqGridSalary";
var gridpagerid2 = "#jqGridPagerSalary";

/*teacher_id =':;';
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
)*/

var salaryColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Teacher', hidden:true, template:common.selectTemplate('teacher_id', 50, false, ' * ',
			'select', teacher_id, teacher_value, true,2,1,'')},
	{label: 'Salary Date',  template:common.dateTemplate('salary_date', 50, true, ' * ',2,2)},
	{label: 'Worked Hours', template:common.numberTemplate('worked_hours', 50, false, ' &nbsp; ',2,3, {maxlength: 4})},
	{label: 'Total Salary', template:common.numberTemplate('total_salary', 50, false, ' &nbsp; ',3,1, {maxlength: 4})},
	{label: 'Deduction', template:common.numberTemplate('deduction', 50, false, ' &nbsp; ',3,2, {maxlength: 4})},
	{label: 'Payment', template:common.numberTemplate('payment', 50, true, ' * ',3,3, {maxlength: 4})}
];


// activate the toolbar searching
//$(gridid1).jqGrid('filterToolbar',common.showFilterOptions);

function pushDataSalary(result) {
	debugger;
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			teacher_id: item.school_teacher_id,
			salary_date: item.salary_date,
			worked_hours: item.worked_hours,
			total_salary: item.total_salary,
			deduction: item.deduction,
			payment: item.payment
		});
	}
	return arrayData;
}