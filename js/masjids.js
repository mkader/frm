var gridid = "#jqGrid";
var gridpagerid  = "#jqGridPager";
var state_id =':;';
var state_value =':[All];';
common.ajaxCall(false, "get", "json/select.json", null,
	function( response ) {
		state_id += response['state_id'][0];
		state_value += response['state_id'][0];
	},
	function( response ) {
		common.errorAlert(response.responseText);
	}
)

var masjidColModel = [
	{label: 'id', template:common.idTemplate('id',1,1)},
	{label: 'Name', template:common.textTemplate('name', 100, true, ' * ', true,2,1)},
   	{label: 'Address', hidden:true,template:common.textTemplate('address', 100, true, ' * ', true,3,1)},
	{label: 'City', template:common.textTemplate('city', 50, true, ' * ', true,3,2)},
	{label: 'State', template:common.selectTemplate('state', 50, true, ' * ',
			'select', state_id, state_value, true,4,1)},
	{label: 'Zip Code',  hidden:true,template:common.textTemplate('zipcode', 50, true, ' * ', true,4,2)},
	{label: 'E-mail', hidden:true,formatter:'email', template:common.textTemplate('email', 100, false, ' &nbsp; ', true,5,1)},
	{label: 'Phone', template:common.phoneTemplate('phone', 50, false, ' &nbsp; ', true,5,2)},
	{label: 'Website', hidden:true,template:common.textTemplate('website', 50, false, ' &nbsp; ', true,6,1)},
	{label: 'Contact Name', template:common.textTemplate('contact_name', 100, true, ' * ', true,7,1)},
   	{label: 'Contact E-mail', formatter:'email', template:common.textTemplate('contact_email', 100, false, ' &nbsp; ', true,7,2)},
	{label: 'Contact Phone', template:common.phoneTemplate('contact_phone', 50, false, ' &nbsp; ', true,8,1)},
	{label: 'Comments', template:common.textAreaTemplate('comments', 100, true, " &nbsp; ", true,9,1, '2', '23')}
];

function beforeSubmit(postdata, form) {
	var validate = true;
	if (postdata.email.length>0) validate = common.validateEmail(postdata.email);
	if (postdata.contact_email.length>0) validate = common.validateEmail(postdata.contact_email);
	return[validate,'E-mail: Field is not valid'];
};

$(gridid).jqGrid(common.gridOptions(gridpagerid, masjidColModel, 'Masjid List',
	'masjids.php', 900, null, null, 100, 230, common.onDblClickRow));

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
	common.setGridData(gridid, "get", "masjids.php", {action: 'masjidlist'}, pushData)
}

function pushData(result) {
	var arrayData = [];
	for (var i = 0; i < result.length; i++) {
		var item = result[i];
		arrayData.push({
			id: item.id,
			name: item.name,
			address: item.address,
			city: item.city,
			state: item.state,
			zipcode: item.zipcode,
			email: item.email,
			phone: item.phone,
			contact_name: item.contact_name,
			contact_email: item.contact_email,
			contact_phone: item.contact_phone,
			comments: item.comments,
			website: item.website
		});
	}
	return arrayData;
}