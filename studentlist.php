<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridStudent"></table>
<div id="jqGridPagerStudent"></div>

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

<script type="text/javascript" src="js/students.js" ></script>

<script type="text/javascript">

function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmitStudent, null), {
		beforeSubmit: beforeShowFormStudent,
	})
}

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
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitStudent, beforeShowFormStudent));
			},
			'del': function (t) {
				var grid = $(gridid1);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitStudent));
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

$(gridid1).jqGrid(common.gridOptions(gridpagerid1, studentColModel, 'Student List',
	'students.php',900,null, initGrid, 5, 115, common.onDblClickRow));

function afterSubmitStudent(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchStudentData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormStudent(form) {
	$("#enrollment_id", form).val(<?php echo $did ?>);
	$('#enrollment_id',form).attr('disabled','true');
};

$(gridid1).navGrid(gridpagerid1,
	gridFooterIcons,
	common.modalEdit('auto','', afterSubmitStudent, beforeShowFormStudent),
	common.modalCreate('auto', afterSubmitStudent, beforeShowFormStudent),
	common.modalDelete(afterSubmitStudent)
);

fetchStudentData(<?php echo $did ?>);

function fetchStudentData(did) {
	common.setGridData(gridid1, "get", "students.php", {action: 'enrollmentstudentlist',id:did}, pushData1)
}

</script>
<?php
}
?>