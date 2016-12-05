var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var state_id =':;';
var state_value =':[All];';
var teacher_id =':;';
var teacher_value =':[All];';
var d = (new Date()).getTime();

var userColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Name', template:common.textTemplate('name', 100, true, ' * ', true,2,1)},
	{label: 'SSN',  hidden:true, template:common.textTemplate('ssn', 20, false, ' &nbsp; ', true,2,2)},
	{label: 'Join Date',  hidden:true, template:common.dateTemplate('join_date', 50, true, ' * ',2,3)},
	{label: 'Active', template:common.selectTemplate('active', 50, true, ' &nbsp; ',
			'checkbox', active_id, active_value, true,2,4,'')},
	{label: 'Full Time', template:common.selectTemplate('full_time', 50, true, ' &nbsp; ',
			'checkbox', active_id, active_value, true,3,1,'')},
	{label: 'Volunteer', template:common.selectTemplate('volunteer', 50, true, ' &nbsp; ',
			'checkbox', active_id, active_value, true,3,2,'')},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, false, ' &nbsp; ', true,3,3)},
	{label: 'E-mail', formatter:'email', template:common.textTemplate('email', 100, false, 
			' &nbsp; ', true,3,4)},
	{label: 'Address',  hidden:true, template:common.textTemplate('address', 100, false, ' &nbsp; ', true,4,1)},
	{label: 'City', hidden:true,template:common.textTemplate('city', 50, false, ' &nbsp; ', true,4,2)},
	{label: 'Zip Code', hidden:true, template:common.textTemplate('zipcode', 50, false, ' &nbsp; ', true,4,3)},
	{label: 'Fee Deduction',  hidden:true,  formatter:'currency',
		formatoptions:{thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "},
		template:common.numberTemplate('fee_deduction', 50, false, ' &nbsp; ',4,4, {maxlength: 8})},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ', true,5,1, '2', '23')},
	{label: 'Resign Date', hidden:true, template:common.dateTemplate('resign_date', 50, false, ' &nbsp; ',5,2)}
	
];

function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmit, null), {
		beforeSubmit: beforeSubmit,
	})
}

function beforeSubmit(postdata, form) {
	var validate = true;
	var msg =''
	if (postdata.email.length>0) {
		validate = common.validateEmail(postdata.email);
		msg+='Email ';
	}
	return[validate,msg +' is not valid'];
};

function loadTeacherInfo(id) {
	$( "#tabs" ).show();
	updateContent("attendancelist.php?did="+id+"&nocache="+d, "#attendancelistid");
	updateContent("salarylist.php?did="+id+"&nocache="+d, "#salarylistid");
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, userColModel, 'Teacher List',
	'teachers.php', 900, loadTeacherInfo, null, 100, 150, common.onDblClickRow));

$(gridid).jqGrid('filterToolbar',common.showFilterOptions);

$(gridid).navGrid(gridpagerid,
	gridFooterIcons,
	$.extend( common.modalEdit('auto', '',common.afterSubmit, null), {
		beforeSubmit: beforeSubmit,
	}),
	$.extend( common.modalCreate('auto',common.afterSubmit, null), {
		beforeSubmit: beforeSubmit,
	}),
	common.modalDelete(common.afterSubmit),
	{},{width: 900}
);

fetchGridData();

function fetchGridData() {
	common.setGridData(gridid, "get", "teachers.php", {action: 'teacherlist'}, pushData)
}

function pushData(result) {
	debugger;
	var arrayData = [];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		var home_address = '';
		if(item.address.length>0) home_address+=item.address;
		if (item.city.length>0 && home_address.length>0) home_address+=', '+ item.city;
		else home_address+=item.city;
		//if(item.state.length>0 && home_address.length>0) home_address+=', '+ item.state
		//else home_address+=item.state;
		if(item.zipcode.length>0 && home_address.length>0) home_address+=' - '+ item.zipcode
		else home_address+=item.zipcode;
		arrayData.push({
			id: item.id,
			name: item.name,
			home_address: home_address,
			address: item.address,
			city: item.city,
			zipcode: item.zipcode,
			email: item.email,
			phone: item.phone,
			ssn: item.ssn,
			comments: item.comments,
			join_date: item.join_date,
			resign_date: item.resign_date,
			full_time: item.full_time,
			fee_deduction: item.fee_deduction,
			active: item.active,
			volunteer:item.volunteer,
		});
	}
	return arrayData;
}