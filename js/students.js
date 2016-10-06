var gridid1 = "#jqGridStudent";
var gridpagerid1 = "#jqGridPagerStudent";

enrollment_id =':;';
enrollment_value =':[All];';
var d = (new Date()).getTime();

common.ajaxCall(false, "get", "json/enrollment.json?nocache="+d, null,
	function( response ) {
		enrollment_id += response['enrollment_id'][0];
		enrollment_value += response['enrollment_value'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var gender_id = 'M:Male;F:Female';
var studentColModel = [

	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Ennrollment', hidden:true, template:common.selectTemplate('enrollment_id', 50, false, ' * ',
			'select', enrollment_id, enrollment_value, true,2,1,'')},
	{label: 'Date of Join', hidden:true, template:common.dateTemplate('date_of_join', 50, true, ' * ',2,2)},
	{label: 'Active', template:common.selectTemplate('active', 50, true, ' &nbsp; ', 'checkbox', active_id, active_value, true,2,3,'')},
	{label: 'Name', template:common.textTemplate('full_name', 100, true, ' * ', false,2,1)},
   	{label: 'Name First', hidden:true, template:common.textTemplate('first_name', 100, true, ' * ', true,3,1)},
	{label: 'Middle', hidden:true, template:common.textTemplate('middle_name', 100, false, ' &nbsp; ', true,3,2)},
	{label: 'Last', hidden:true, template:common.textTemplate('last_name', 100, true, ' * ', true,3,3)},
	{label: 'Gender', template:common.selectTemplate('gender', 50, true, ' * ', 'select', gender_id, gender_id,true,4,1,'')},
	{label: 'Date of Birth', hidden:true, template:common.dateTemplate('dob', 50, false, ' &nbsp; ',4,2)},
	{label: 'Age', formatter:'currency', formatoptions:{thousandsSeparator: "", decimalPlaces: 0, prefix: ""},
		template:common.numberTemplate('age', 50, false, ' &nbsp; ',4,3, {maxlength: 8})},
	{label: 'Public School Grade', hidden:true,template:common.phoneTemplate('public_school_grade', 50, false, ' &nbsp; ', true,5,1)},
	//{label: 'Physician Name',formatter:'email', template:common.textTemplate('physician_name', 100, false, ' &nbsp; ', true,6,1)},
	//{label: 'Phone', template:common.phoneTemplate('physician_phone', 100, false, ' &nbsp; ', true,6,2)},
	//{label: 'Address', hidden:true,template:common.textTemplate('physician_address', 50, false, ' &nbsp; ', true,6,3)},
	//{label: 'Emergency Hospital', hidden:true,template:common.textTemplate('emergency_hospital', 50, false, ' &nbsp; ', true,7,1)},
	{label: 'Reading Level Arabic', hidden:true, template:common.textTemplate('reading_level_arabic', 50, false, ' &nbsp; ', true,5,2)},
	{label: 'Quran', hidden:true, template:common.textTemplate('reading_level_quran', 50, false, ' &nbsp; ', true,5,3)},
	{label: 'Medical Conditions', template:common.textAreaTemplate('medical_conditions', 100, false, ' &nbsp; ', true,6,1, '2','23')},
	{label: 'Allergies Details',  template:common.textAreaTemplate('allergies_details', 100, false, ' &nbsp; ', true,6,2, '2','23')},
	{label: 'Comments', hidden:true, template:common.textAreaTemplate('comments', 100, false, " &nbsp; ", true, 6,3, '2', '23')}
    //enrollment_id,
];

// activate the toolbar searching
//$(gridid1).jqGrid('filterToolbar',common.showFilterOptions);

function pushData1(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		var full_name = item.first_name;
		if(item.middle_name.length>0) full_name+=' '+item.middle_name;
		full_name+=' '+item.last_name;
		arrayData.push({
			id: item.id,
		    enrollment_id: item.enrollment_id,
		    full_name: full_name,
		    first_name: item.first_name,
		    middle_name: item.middle_name,
		    last_name: item.last_name,
		    gender: item.gender,
		    dob: item.dob,
		    age: item.age,
		    public_school_grade: item.public_school_grade,
		    /*physician_name: item.physician_name,
		    physician_phone: item.physician_phone,
		    physician_address: item.physician_address,
		    emergency_hospital:item.emergency_hospital,*/
		    medical_conditions: item.medical_conditions,
		    allergies_details: item.allergies_details,
		    reading_level_arabic: item.reading_level_arabic,
		    reading_level_quran: item.reading_level_quran,
		    date_of_join: item.date_of_join,
		    comments: item.comments,
		    active: item.active,
		});
	}
	return arrayData;
}