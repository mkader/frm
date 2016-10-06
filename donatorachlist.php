<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridACH"></table>
<div id="jqGridPagerACH"></div>

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

<script>
var did = "<?php echo $did; ?>";
</script>
<script type="text/javascript" src="js/achs.js" ></script>

<script type="text/javascript">
function initGrid3() {
	$(this).contextMenu('contextMenu', {
		bindings: {
			'edit': function (t) {
				var grid = $(gridid3);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.editGridRow(selRowId,editSettings());
			},
			'add': function (t) {
				var grid = $(gridid3);
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitDonatorsACH, beforeShowFormDonatorsACH));
			},
			'del': function (t) {
				var grid = $(gridid3);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitDonatorsACH));
			}
		},
		onContextMenu: function (event, menu) {
			var rowId = $(event.target).parent("tr").attr("id")
			var grid = $(gridid3);
			grid.setSelection(rowId);

			return true;
		}
	});
}

$(gridid3).jqGrid(common.gridOptions(gridpagerid3, achColModel,
	'ACH List', 'achs.php',900,null, initGrid3, 5, 115, common.onDblClickRow));

function afterSubmitDonatorsACH(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchDonatorsACHData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormDonatorsACH(form) {
	$("#donator_id", form).val(<?php echo $did ?>);
	$('#donator_id',form).attr('disabled','true');
	common.numberOnly('#cycle');
	common.decimalOnly('#amount');
};

function editSettings() {
	return $.extend(common.modalEdit('auto', '',afterSubmitDonatorsACH, beforeShowFormDonatorsACH));
}

$(gridid3).navGrid(gridpagerid3,
	gridFooterIcons,
	$.extend( common.modalEdit('auto', '',afterSubmitDonatorsACH, beforeShowFormDonatorsACH)),
	$.extend( common.modalCreate('auto', afterSubmitDonatorsACH, beforeShowFormDonatorsACH)),
	common.modalDelete(afterSubmitDonatorsACH)
);

fetchDonatorsACHData(<?php echo $did ?>);

function fetchDonatorsACHData(did) {
	common.setGridData(gridid3, "get", "achs.php", {action: 'donatorsachlist',id:did}, pushDataACH)
}

</script>
<?php
}
?>