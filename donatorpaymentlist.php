<?php
if (isset($_GET['did']))
	$did = $_GET['did'];
?>
<table id="jqGridPayment"></table>
<div id="jqGridPagerPayment"></div>

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
var pledge_id =':;';
var pledge_value =':[All];';
var taxyear = "<?php echo date("Y"); ?>";
common.ajaxCall(false, "get", "pledges.php?action=donatorspledgelistjson&id=<?php echo $did ?>", null,
	function( response ) {
		var res = common.JSONParse(response);
		if (res['error']) {
			common.errorAlert(event, res['message']);
		}else if (res['success']) {
			var item = common.JSONParse(res['data']);
			pledge_id += item.pledge_id;
			pledge_value += item.pledge_value;
		}
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)
</script>
<script type="text/javascript" src="js/payments.js" ></script>

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
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitDonatorsPledge, beforeShowFormDonatorsPledge));
			},
			'del': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitDonatorsPledge));
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

$(gridid2).jqGrid(common.gridOptions(gridpagerid2, paymentColModel, 'Payment List', 'payments.php',900,null, initGrid2));

function afterSubmitDonatorsPayment(response) {
	var res = common.JSONParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchDonatorsPaymentData(<?php echo $did ?>);
		return [true];
	}
};

function beforeShowFormDonatorsPayment(form) {
	$("#donator_id", form).val(<?php echo $did ?>);
	$('#donator_id',form).attr('disabled','true');
};


function editSettings() {
	return $.extend(common.modalEdit('auto','',afterSubmitDonatorsPayment), {beforeShowForm: beforeShowFormDonatorsPayment});
}

$(gridid2).navGrid(gridpagerid2,
	gridFooterIcons,
	editSettings(),
	common.modalCreate('auto', afterSubmitDonatorsPayment, beforeShowFormDonatorsPayment),
	common.modalDelete(afterSubmitDonatorsPayment)
);

fetchDonatorsPaymentData(<?php echo $did ?>);

function fetchDonatorsPaymentData(did) {
	common.setGridData(gridid2, "get", "payments.php", {action: 'donatorspaymentlist',id:did}, pushData2)
}

</script>