var userColModel = [
	{label: 'id', template:common.idTemplate('id',7,1)},
	{label: 'Name', template:common.textTemplate('name', 100, true, ' * ',true,1,1)},
	{label: 'Password', template:common.passwordTemplate('password',2,1)},
	{label: 'E-mail', formatter:'email', template:common.textTemplate('email', 100, true, ' * ',true,3,1)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, true, ' * ',true,4,1)},
	{label: 'User Type', template:common.selectTemplate('user_type', 50, true, ' * ',
		'select', ':;1:Super Admin;2:Admin', ':[All];Super Admin:Super Admin;Admin:Admin', true,5,1)},
	{label: 'Active', template:common.selectTemplate('active', 50, true, ' &nbsp; ', 
		'checkbox', '1:Yes;2:No', ':[All];Yes:Yes;No:No', true,6,1)}
];

function editSettings() {
	return $.extend(common.modalEdit('auto', "<br><font color='red'>Leave password blank if dont want to change</font>"), {
		beforeInitData: function(formid) {
			$("#jqGrid").jqGrid('setColProp', 'password',
					{formoptions: {elmprefix: "  &nbsp;  ", rowpos: 2, colpos: 1}},
					{editoptions:{value:''}});
		},
		beforeShowForm: function(formid) {
			$("#jqGrid").jqGrid('setColProp', 'password', {editrules: {required: false}});
		},
		beforeSubmit: function(postdata, formid) {
			return[common.validateEmail(postdata.email),'E-mail: Field is not valid'];
		},
	});
}

//$(document).ready(function()
/*$(function(){
	if ($("#userlist").length>0){
		$("#userlist").ready(function () {*/
			$("#jqGrid").jqGrid(common.gridOptions(userColModel, 'User List', 'users.php'));
			/*	numopts : ['eq','ne', 'lt', 'le', 'gt', 'ge', 'nu', 'nn', 'in', 'ni'],
				sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge', 'bw', 'bn', 'ew', 'en', 'cn', 'nc', 'nu', 'nn', 'in', 'ni']*/

			$('#jqGrid').jqGrid('filterToolbar',common.showFilterOptions);

			$("#jqGrid").navGrid("#jqGridPager",
				gridFooterIcons,
				editSettings(),
				$.extend( common.modalCreate('auto'), {
					// options for the Add Dialog
					//mtype: "post",
					/*onclickSubmit : function(params, posdata) {
						fetchGridData();
					},*/
					beforeInitData: function(formid) {
						$("#jqGrid").jqGrid('setColProp', 'password', 
								{formoptions: {elmprefix: " * ", rowpos: 2, colpos: 1}});
					},
					beforeShowForm: function(formid) {
						$("#jqGrid").jqGrid('setColProp', 'password', {editrules: {required: true}});
					},
					beforeSubmit: function(postdata, formid) {
						return[common.validateEmail(postdata.email),'E-mail: Field is not valid'];
					},
					/*afterComplete: function(response, postdata, formid) {
						var res = common.decode(response['responseText'])
						if (res['error']) {
							return [false, '', 'Error: ' + res['message']];
							//return 'Error: ' + res['message'];
						} else if (res['success']) {
							location.href="default.php";
						}
						//console.log(response);
						//console.log(postdata);
						//console.log(formid);
					}*/
				}),
				common.modalDelete()
	        );

			fetchGridData();

/*		});
	}
});*/

function fetchGridData() {
	common.setGridData("get", "users.php", {action: 'userlist'}, pushData) 
}

function pushData(result) {
	var arrayData = [];
	//var result = res['data'];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			password: '',/*item.password,*/
			name: item.name,
			email: item.email,
			phone: item.phone,
			user_type: item.user_type,
			active: item.active
		});
	}
	return arrayData;
}

