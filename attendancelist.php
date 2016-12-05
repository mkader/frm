<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridAttendance"></table>
<div id="jqGridPagerAttendance"></div>

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

<script type="text/javascript" src="js/attendances.js" ></script>

<script type="text/javascript">

function editSettings() {
	return $.extend( common.modalEdit('auto', '',common.afterSubmitAttendance, null), {
		beforeSubmit: beforeShowFormAttendance,
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
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitAttendance, beforeShowFormAttendance));
			},
			'del': function (t) {
				var grid = $(gridid1);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitAttendance));
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

$(gridid1).jqGrid(common.gridOptions(gridpagerid1, attendanceColModel, 'Attendance List',
	'attendances.php',900,null, initGrid, 5, 115, common.onDblClickRow));

function afterSubmitAttendance(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchAttendanceData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormAttendance(form) {
	$("#teacher_id", form).val(<?php echo $did ?>);
	$('#teacher_id',form).attr('disabled','true');
};

function beforeShowFormAttendanceCreate(form) {
	$("#teacher_id", form).val(<?php echo $did ?>);
	$('#teacher_id',form).attr('disabled','true');
	$("#time_in", form).val("10:00:00");
	$("#time_out", form).val("02:00:00");
	$("#hours", form).val("4");
};

$(gridid1).navGrid(gridpagerid1,
	gridFooterIcons,
	common.modalEdit('auto','', afterSubmitAttendance, beforeShowFormAttendance),
	common.modalCreate('auto', afterSubmitAttendance, beforeShowFormAttendanceCreate),
	common.modalDelete(afterSubmitAttendance)
);

fetchAttendanceData(<?php echo $did ?>);

function fetchAttendanceData(did) {
	common.setGridData(gridid1, "get", "attendances.php", {action: 'teacherattendancelist',id:did}, pushData1)
}

</script>
<?php
}
?>