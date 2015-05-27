var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var state_id =':;';
var state_value =':[All];';
var donator_id =':;';
var donator_value =':[All];';
var d = (new Date()).getTime();

common.ajaxCall(false, "get", "json/select.json", null,
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
	{label: 'Name (Company)', template:common.textTemplate('name_companyname', 100, true, ' * ', false,2,1)},
   	{label: 'Name', hidden:true, template:common.textTemplate('name', 100, true, ' * ', true,3,1)},
   	{label: 'Company Name', hidden:true, template:common.textTemplate('company_name', 100, false, ' &nbsp; ', true,3,2)},
	{label: 'Address', template:common.textTemplate('address', 100, false, ' &nbsp; ', false,4,1)},
	{label: 'Address', hidden:true, template:common.textTemplate('address1', 100, false, ' &nbsp; ', true,5,1)},
	/*{label: '',  hidden:true, template:common.textTemplate('address2', 50, false, ' &nbsp; ', true,5,2)},*/
	{label: 'City', hidden:true,template:common.textTemplate('city', 50, false, ' &nbsp; ', true,5,2)},
	{label: 'State', hidden:true,template:common.selectTemplate('state', 50, false, ' &nbsp; ',
			'select', state_id, state_value, true,6,1, 'WI')},
	{label: 'Zip Code', hidden:true, template:common.textTemplate('zipcode', 50, false, ' &nbsp; ', true,6,2)},
	{label: 'E-mail', formatter:'email', template:common.textTemplate('email', 100, false, ' &nbsp; ', true,7,1)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, false, ' &nbsp; ', true,7,2)},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, false, " &nbsp; ", true,8,1, '2', '23')}
];

/*function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmit, null), {
		beforeSubmit: beforeSubmit,
	})
}*/

function beforeSubmit(postdata, form) {
	var validate = true;
	if (postdata.email.length>0) validate = common.validateEmail(postdata.email);
	return[validate,'E-mail: Field is not valid'];
};

function loadPledges(id) {
	$( "#tabs" ).show();
	updateContent("donatorpledgelist.php?did="+id, "#pledgelistid");
	updateContent("donatorpaymentlist.php?did="+id, "#paymentlistid");
	updateContent("donatorachlist.php?did="+id, "#achlistid");
}

$(gridid).jqGrid(common.gridOptions(gridpagerid, userColModel, 'Donator List',
	'donators.php', 900, loadPledges, null, 100, 150, common.onDblClickRow));

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
	common.setGridData(gridid, "get", "donators.php", {action: 'donatorlist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		var name_companyname = item.name;
		if(item.company_name.length>0) name_companyname+=' ('+item.company_name+')';
		var address = '';
		if(item.address1.length>0) address+=item.address1;
		//if(item.address2.length>0 && address.length>0) address+=', '+item.address2;
		//else address+=item.address2;
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
			//address2: item.address2,
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