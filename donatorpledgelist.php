<?php
if (isset($_GET['did']))
	$did = $_GET['did'];
?>
<table id="jqGridPledge"></table>
<div id="jqGridPagerPledge"></div>

<div class="contextMenu" id="contextMenu" style="display:none">
        <ul style="width: 300px; font-size: 65%;">
            <li id="add">
                <span class="ui-icon ui-icon-plus" style="float:left"></span>
                <span style="font-size:100%; font-family:Verdana">Add Row</span>
            </li>
            <li id="edit">
                <span class="ui-icon ui-icon-pencil" style="float:left"></span>
                <span style="font-size:100%; font-family:Verdana">Edit Row</span>
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
	debugger;
	$(this).contextMenu('contextMenu', {
		bindings: {
			'edit': function (t) {
				alert("Edit Row Command Selected");
			},
			'add': function (t) {
				var grid = $(gridid1);
				common.modalCreate('auto', afterSubmitDonatorsPledge);
			},
			'del': function (t) {
				alert("Delete Row Command Selected");
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

$(gridid1).jqGrid(common.gridOptions(gridpagerid1, pledgeColModel, 'Pledge List', 'pledges.php',900,null, initGrid));

	function afterSubmitDonatorsPledge(response) {
		debugger;
		var res = common.decode(response.responseText)
		if (res['error']) {
			return [false, 'Error: ' + res['message']];
		} else {
			debugger;
			fetchDonatorsPledgeData(<?php echo $did ?>);
			return [true];
		}
	};

	function editSettings() {
		return common.modalEdit('auto','',afterSubmitDonatorsPledge);
	}

	$(gridid1).navGrid(gridpagerid1,
		gridFooterIcons,
		editSettings(),
		common.modalCreate('auto', afterSubmitDonatorsPledge),
		common.modalDelete(afterSubmitDonatorsPledge)
	);

	fetchDonatorsPledgeData(<?php echo $did ?>);

	function fetchDonatorsPledgeData(did) {
		debugger;
		common.setGridData(gridid1, "get", "pledges.php", {action: 'donatorspledgelist',id:did}, pushData1)
	}

</script>