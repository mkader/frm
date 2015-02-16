<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridPledge"></table>
<div id="jqGridPagerPledge"></div>

<div class="contextMenu" id="contextMenu" style="display:none">
        <ul style="width: 400px; font-size: 65%;">
            <li id="add">
                <span class="ui-icon ui-icon-plus" style="float:left"></span>
                <span style="font-size:100%; font-family:Verdana">Add Record</span>
            </li>
            <li id="edit">
                <span class="ui-icon ui-icon-pencil" style="float:left"></span>
                <span style="font-size:100%; font-family:Verdana">Edit Record</span>
            </li>
            <li id="del">
                <span class="ui-icon ui-icon-trash" style="float:left"></span>
                <span style="font-size:100%; font-family:Verdana">Delete Row</span>
            </li>
        </ul>
    </div>

<script type="text/javascript" src="js/pledges.js" ></script>

<script type="text/javascript">
function initGrid() {
	$(this).contextMenu('contextMenu', {
		bindings: {
			'edit': function (t) {
				var grid = $(gridid1);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');

				grid.editGridRow(selRowId,editSettings());

			},
			'add': function (t) {
				var grid = $(gridid1);
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitDonatorsPledge, beforeShowFormDonatorsPledge));
			},
			'del': function (t) {
				var grid = $(gridid1);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitDonatorsPledge));
			}
		},
		onContextMenu: function (event, menu) {
			var rowId = $(event.target).parent("tr").attr("id")
			var grid = $(gridid1);
			grid.setSelection(rowId);

			return true;
		}
	});
}

$(gridid1).jqGrid(common.gridOptions(gridpagerid1, pledgeColModel, 'Pledge List',
	'pledges.php',900,null, initGrid, 5, 115, common.onDblClickRow));

function afterSubmitDonatorsPledge(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchDonatorsPledgeData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormDonatorsPledge(form) {
	$("#donator_id", form).val(<?php echo $did ?>);
	$('#donator_id',form).attr('disabled','true');
	common.decimalOnly('#amount');
};

$(gridid1).navGrid(gridpagerid1,
	gridFooterIcons,
	common.modalEdit('auto','', afterSubmitDonatorsPledge, beforeShowFormDonatorsPledge),
	common.modalCreate('auto', afterSubmitDonatorsPledge, beforeShowFormDonatorsPledge),
	common.modalDelete(afterSubmitDonatorsPledge)
);

fetchDonatorsPledgeData(<?php echo $did ?>);

function fetchDonatorsPledgeData(did) {
	common.setGridData(gridid1, "get", "pledges.php", {action: 'donatorspledgelist',id:did}, pushData1)
}

</script>
<?php
} 
?>