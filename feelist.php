<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
	$did = $_GET['did'];
?>
<table id="jqGridFee"></table>
<div id="jqGridPagerFee"></div>

<script>
var did = "<?php echo $did; ?>";
</script>
<script type="text/javascript" src="js/fees.js" ></script>

<script type="text/javascript">
function initGrid2() {
	$(this).contextMenu('contextMenu', {
		bindings: {
			'edit': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.editGridRow(selRowId,editSettings());
			},
			'add': function (t) {
				var grid = $(gridid2);
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitFee, beforeShowFormFee));
			},
			'del': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitFee));
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

$(gridid2).jqGrid(common.gridOptions(gridpagerid2, feeColModel,
	'Fee List', 'fees.php',900,null, initGrid2, 5, 115, common.onDblClickRow));

function afterSubmitFee(response) {
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchFeeData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormFee(form) {
	$("#enrollment_id", form).val(<?php echo $did ?>);
	$('#enrollment_id',form).attr('disabled','true');
	common.decimalOnly('#amount');
};

$(gridid2).navGrid(gridpagerid2,
	gridFooterIcons,
	common.modalEdit('auto', '',afterSubmitFee, beforeShowFormFee),
	common.modalCreate('auto', afterSubmitFee, beforeShowFormFee),
	common.modalDelete(afterSubmitFee)
);

fetchFeeData(<?php echo $did ?>);

function fetchFeeData(did) {
	common.setGridData(gridid2, "get", "fees.php", {action: 'enrollmentfeelist',id:did}, pushDataFee)
}

</script>
<?php
}
?>