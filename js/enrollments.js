var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var state_id =':;';
var state_value =':[All];';
var enrollment_id =':;';
var enrollment_value =':[All];';
var d = (new Date()).getTime();

common.ajaxCall(false, "get", "json/select.json?nocache="+d, null,
	function( response ) {
		state_id += response['state_id'][0];
		state_value += response['state_id'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var userColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Father Name', template:common.textTemplate('father_name', 100, true, ' * ', true,2,1)},
   	{label: 'Cell', hidden:true,template:common.phoneTemplate('father_cell', 50, false, ' &nbsp; ', true,2,2)},
	{label: 'Work', hidden:true,template:common.phoneTemplate('father_work', 50, false, ' &nbsp; ', true,2,3)},
	{label: 'E-mail', hidden:true,formatter:'email', template:common.textTemplate('father_email', 100, false, ' &nbsp; ', true,2,4)},
	{label: 'Mother Name', template:common.textTemplate('mother_name', 100, true, ' * ', true,3,1)},
   	{label: 'Cell', hidden:true,template:common.phoneTemplate('mother_cell', 50, false, ' &nbsp; ', true,3,2)},
	{label: 'Work', hidden:true,template:common.phoneTemplate('mother_work', 50, false, ' &nbsp; ', true,3,3)},
	{label: 'E-mail', hidden:true,formatter:'email', template:common.textTemplate('mother_email', 100, false, ' &nbsp; ', true,3,4)},
	{label: 'Address', template:common.textTemplate('address', 100, false, ' &nbsp; ', true,4,1)},
	{label: 'City', hidden:true,template:common.textTemplate('city', 50, false, ' &nbsp; ', true,4,2)},
	/*{label: 'State', hidden:true,template:common.selectTemplate('state', 50, false, ' &nbsp; ',
			'select', state_id, state_value, true,5,2, 'WI')},*/
	{label: 'Zip Code', hidden:true, template:common.textTemplate('zipcode', 50, false, ' &nbsp; ', true,4,3)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, false, ' &nbsp; ', true,4,4)},
	{label: 'Language Primary', hidden:true, template:common.textTemplate('language_primary', 50, false, ' &nbsp; ', true,5,1)},
	{label: 'Other', hidden:true, template:common.textTemplate('language_other', 50, false, ' &nbsp; ', true,5,2)},
	{label: 'Physician Name',formatter:'email', template:common.textTemplate('physician_name', 100, false, ' &nbsp; ', true,6,1)},
	{label: 'Phone', template:common.phoneTemplate('physician_phone', 100, false, ' &nbsp; ', true,6,2)},
	{label: 'Address', hidden:true,template:common.textTemplate('physician_address', 50, false, ' &nbsp; ', true,6,3)},
	{label: 'Hospital', hidden:true,template:common.textTemplate('emergency_hospital', 50, false, ' &nbsp; ', true,6,4)},
	{label: 'Emergency contact', template:common.textTemplate('emergency_contact1', 100, false, ' &nbsp; ', true,7,1)},
	{label: 'Relation', hidden:true,template:common.textTemplate('emergency_relation1', 100, false, ' &nbsp; ', true,7,2)},
   	{label: 'Phone', template:common.phoneTemplate('emergency_phone1', 50, false, ' &nbsp; ', true,7,3)},
   	{label: 'Emergency contact', hidden:true,template:common.textTemplate('emergency_contact2', 100, false, ' &nbsp; ', true,8,1)},
	{label: 'Relation', hidden:true,template:common.textTemplate('emergency_relation2', 100, false, ' &nbsp; ', true,8,2)},
   	{label: 'Phone', hidden:true,template:common.phoneTemplate('emergency_phone2', 50, false, ' &nbsp; ', true,8,3)},
	{label: 'Comments', hidden:true,template:common.textAreaTemplate('comments', 100, false, ' &nbsp; ', true,8,4, '2', '23')}
];

function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmit, null), {
		beforeSubmit: beforeSubmit,
	})
}

function beforeSubmit(postdata, form) {
	var validate = true;
	var msg =''
	if (postdata.father_email.length>0) {
		validate = common.validateEmail(postdata.father_email);
		msg+='Father ';
	}
	if (postdata.mother_email.length>0) {
		validate = common.validateEmail(postdata.mother_email);
		if (msg.length>0) msg+=', Mother ';
		else msg+='Mother';
	}
	return[validate,msg +' E-mail: Field is not valid'];
};

function loadStudents(id) {
	$( "#tabs" ).show();
	updateContent("studentlist.php?did="+id, "#studentlistid");
	updateContent("feelist.php?did="+id+"&nocache="+d, "#feelistid");
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, userColModel, 'Enrollments List',
	'enrollments.php', 900, loadStudents, null, 100, 150, common.onDblClickRow));

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
	common.setGridData(gridid, "get", "enrollments.php", {action: 'enrollmentlist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		//var father_name = item.father_name;
		//var father_cell = item.father_cell;
		//var father_work = item.father_work;
		//var father_email = item.father_email;
		//var mother_name = item.mother_name;
		//var mother_cell = item.mother_cell;
		//var mother_work = item.mother_work;
		//var mother_email = item.mother_email;
		var home_address = '';
		if(item.address.length>0) home_address+=item.address;
		if (item.city.length>0 && home_address.length>0) home_address+=', '+ item.city;
		else home_address+=item.city;
		if(item.state.length>0 && home_address.length>0) home_address+=', '+ item.state
		else home_address+=item.state;
		if(item.zipcode.length>0 && home_address.length>0) home_address+=' - '+ item.zipcode
		else home_address+=item.zipcode;
		//var phone = item.phone;
		//var language_primary = item.language_primary;
		//var language_other = item.language_other;
		//var emergency_contact1 = item.emergency_contact1;
		//var emergency_relation1 = item.emergency_relation1;
		//var emergency_phone1 = item.language_other;
		//var emergency_contact2 = item.emergency_contact2;
		//var emergency_phone2 = item.emergency_phone2;
		//var emergency_relation2 = item.emergency_relation2;
		arrayData.push({
			id: item.id,
			father_name: item.father_name,
			father_cell: item.father_cell,
			father_work: item.father_work,
			father_email: item.father_email,
			mother_name: item.mother_name,
			mother_cell: item.mother_cell,
			mother_work: item.mother_work,
			mother_email: item.mother_email,
			home_address: home_address,
			address: item.address,
			city: item.city,
			state: item.state,
			zipcode: item.zipcode,
			email: item.email,
			phone: item.phone,
			language_primary: item.language_primary,
			language_other: item.language_other,
			emergency_contact1: item.emergency_contact1,
			emergency_relation1: item.emergency_relation1,
			emergency_phone1: item.emergency_phone1,
			emergency_contact2: item.emergency_contact2,
			emergency_phone2: item.emergency_phone2,
			emergency_relation2: item.emergency_relation2,
			comments: item.comments,
			physician_name: item.physician_name,
		    physician_phone: item.physician_phone,
		    physician_address: item.physician_address,
		    emergency_hospital:item.emergency_hospital
		});
	}
	return arrayData;
}