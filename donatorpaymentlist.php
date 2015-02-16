<?php
session_start();
require_once('/lib/sessions.class.php');
if (isset($_GET['did']) && Sessions::isValidSession())  {
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
var pledge_id ='0:';
var pledge_value =':[All];';
var taxyear = "<?php echo date("Y"); ?>";
var did = "<?php echo $did; ?>";
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
				grid.editGridRow("new",common.modalCreate('auto', afterSubmitDonatorsPayment, beforeShowFormDonatorsPayment));
			},
			'del': function (t) {
				var grid = $(gridid2);
				var selRowId = $(grid).jqGrid('getGridParam','selrow');
				grid.delGridRow(selRowId,common.modalDelete(afterSubmitDonatorsPayment));
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

$(gridid2).jqGrid(common.gridOptions(gridpagerid2, paymentColModel,
	'Payment List', 'payments.php',900,null, initGrid2, 5, 115, common.onDblClickRow));

function afterSubmitDonatorsPayment(response) {
	//debugger;
	var res = common.jsonParse(response.responseText)
	if (res['error']) {
		return [false, 'Error: ' + res['message']];
	} else {
		fetchDonatorsPaymentData(<?php echo $did ?>);
		return [true];
	}
};

function beforeInitDataDonatorsPayment(form) {
	//debugger;
	common.ajaxCall(false, "get", "pledges.php?action=donatorspledgelistjson&id=<?php echo $did ?>", null,
		function( response ) {
			var res = common.jsonParse(response);
			if (res['error']) {
				common.errorAlert(event, res['message']);
			}else if (res['success']) {
				var item = common.jsonParse(res['data']);
				if ((item.pledge_id).toString().length>0) pledge_id = '0:;'+item.pledge_id;
				//pledge_value += item.pledge_value;
				$(gridid2).setColProp('pledge_id', { editoptions: { value: pledge_id} });
			}
		},
		function( response ) {
			common.errorAlert(event, response.responseText);
		}
	)

}

function beforeShowFormDonatorsPayment(form) {
	$("#donator_id", form).val(<?php echo $did ?>);
	$('#donator_id',form).attr('disabled','true');
	common.numberOnly('#tax_year');
	common.decimalOnly('#amount');
};

$(gridid2).navGrid(gridpagerid2,
	gridFooterIcons,
	$.extend( common.modalEdit('auto', '',afterSubmitDonatorsPayment, beforeShowFormDonatorsPayment), {
		beforeInitData: beforeInitDataDonatorsPayment,
	}),
	$.extend( common.modalCreate('auto', afterSubmitDonatorsPayment, beforeShowFormDonatorsPayment), {
		beforeInitData: beforeInitDataDonatorsPayment,
	}),
	common.modalDelete(afterSubmitDonatorsPayment)
);

fetchDonatorsPaymentData(<?php echo $did ?>);

function fetchDonatorsPaymentData(did) {
	common.setGridData(gridid2, "get", "payments.php", {action: 'donatorspaymentlist',id:did}, pushData2)
}

</script>
<?php
}
?>