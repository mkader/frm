var userColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Name (Company)', template:common.textTemplate('name_companyname', 100, true, ' * ', false,2,1)},
   	{label: 'Name', hidden:true, template:common.textTemplate('name', 100, true, ' * ', true,3,1)},
   	{label: 'Company Name', hidden:true, template:common.textTemplate('company_name', 100, false, ' &nbsp; ', true,3,2)},
	{label: 'Address', template:common.textTemplate('address', 100, false, ' &nbsp; ', false,4,1)},
	{label: 'Address', hidden:true, template:common.textTemplate('address1', 100, false, ' &nbsp; ', true,5,1)},
	{label: '',  hidden:true, template:common.textTemplate('address2', 50, false, ' &nbsp; ', true,5,2)},
	{label: 'City', hidden:true,template:common.textTemplate('city', 50, false, ' &nbsp; ', true,6,1)},
	{label: 'State', hidden:true,template:common.selectTemplate('state', 50, false, ' &nbsp; ',
			'select', ":;1:Super Admin;2:Admin", ':[All];Super Admin:Super Admin;Admin:Admin', true,6,2)},
	{label: 'Zip Code', hidden:true, template:common.textTemplate('zipcode', 50, false, ' &nbsp; ', true,6,3)},
	{label: 'E-mail', formatter:'email', template:common.textTemplate('email', 100, false, ' &nbsp; ', true,7,1)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, false, ' &nbsp; ', true,7,2)},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, " &nbsp; ", true,8,1)}
];

function editSettings() {
	return $.extend( common.modalEdit('auto', ''), {
		beforeSubmit: function(postdata, formid) {
			//debugger;
			var validate = true;
			if (postdata.email.length>0) validate = common.validateEmail(postdata.email);
			return[validate,'E-mail: Field is not valid'];
		},
	})
}

$("#jqGrid").jqGrid(common.gridOptions(userColModel, 'Donator List', 'donators.php'));

$('#jqGrid').jqGrid('filterToolbar',common.showFilterOptions);

$("#jqGrid").navGrid("#jqGridPager",
	gridFooterIcons,
	editSettings(),
	$.extend( common.modalCreate('auto'), {
		beforeSubmit: function(postdata, formid) {
			//debugger;
			var validate = true;
			if (postdata.email.length>0) validate = common.validateEmail(postdata.email);
			return[validate,'E-mail: Field is not valid'];
		},
	}),
	common.modalDelete()
);

fetchGridData();

function fetchGridData() {
	common.setGridData("get", "donators.php", {action: 'donatorlist'}, pushData) 
}

function pushData(result) {
	var arrayData = [];
	//debugger;
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		var name_companyname = item.name;
		if(item.company_name.length>0) name_companyname+=' ('+item.company_name+')';
		var address = '';
		if(item.address1.length>0) address+=item.address1;
		if(item.address2.length>0 && address.length>0) address+=', '+item.address2;
		else address+=item.address2;
		if (item.city.length>0 && address.length>0) address+=', '+ item.city;
		else address+=item.city;
		if(item.state.length>0 && address.length>0) address+=', '+ item.state
		else address+=item.state;
		if(item.zipcode.length>0 && address.length>0) address+=' - '+ item.zipcode
		else address+=item.zipcode;
		arrayData.push({
			id: item.id,
			name_companyname: name_companyname,
			name: item.name,
			address: address,
			address1: item.address1,
			address2: item.address2,
			city: item.city,
			state: item.state,
			zipcode: item.zipcode,
			email: item.email,
			phone: item.phone,
			company_name: item.company_name,
			comments: item.comments
		});
	}
	return arrayData;
}