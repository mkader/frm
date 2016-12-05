<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridSalary"></table>
<div id="jqGridPagerSalary"></div>

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

<script type="text/javascript" src="js/salarys.js" ></script>

<script type="text/javascript">

function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmitSalary, null), {
		beforeSubmit: beforeShowFormSalary,
	})
}

function initGrid() {
	$(this).contextMenu('contextMenu', {
		bindings: {
			'edit': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');

				grid.editGridRow(selRowId,editSettings());

			},
			'add': function (t) {
				var grid = $(gridid2);
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitSalary, beforeShowFormSalary));
			},
			'del': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitSalary));
			}
		},
		onContextMenu: function (event, menu) {
			var rowId = $(event.target).parent("tr").attr("id")
			var grid = $(gridid2);
			grid.setSelection(rowId);

			return true;
		}
	});
}

$(gridid2).jqGrid(common.gridOptions(gridpagerid2, salaryColModel, 'Salary List',
	'salarys.php',900,null, initGrid, 5, 115, common.onDblClickRow));

function afterSubmitSalary(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchSalaryData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormSalary(form) {
	$("#teacher_id", form).val(<?php echo $did ?>);
	$('#teacher_id',form).attr('disabled','true');
};

$(gridid2).navGrid(gridpagerid2,
	gridFooterIcons,
	common.modalEdit('auto','', afterSubmitSalary, beforeShowFormSalary),
	common.modalCreate('auto', afterSubmitSalary, beforeShowFormSalary),
	common.modalDelete(afterSubmitSalary)
);

fetchSalaryData(<?php echo $did ?>);

function fetchSalaryData(did) {
	common.setGridData(gridid2, "get", "salarys.php", {action: 'teachersalarylist',id:did}, pushDataSalary)
}

</script>
<?php
}
?>