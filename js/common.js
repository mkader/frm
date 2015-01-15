var textSearchOptions = {sopt:['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc']};
var numberSearchOptions = ['eq','ne','lt','le','gt','ge'];
var gridFooterIcons = {search: false, view: true, add: true, edit: true, del: true, refresh: true};
var Common = {
	decode:function(a){
		return JSON.parse(a)
	},

	validateEmail: function(email) {
	    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	},

	gridOptions:function(ipager, icolModel, icaption, iediturl, iwidth) {
		//if(typeof(iwidth)==='undefined') iwidth = 900;
		return {colModel: icolModel,
			/*loadComplete: function () {
			    $(this).find(">tbody>tr.jqgrow:visible:odd").addClass("altRow");
			},*/
			viewrecords: true,
	        //autowidth: true,
	        height: "auto",
	        width: iwidth,
			height: 230,
			rowNum: 10,
			ignoreCase: true,
			datatype: 'local',
			multiSort: true,
			altRows: true,
			rownumbers: true,
	        rownumWidth: 25,
	        rowList: [10,20,40],
			pager: ipager,
			mtype:"POST",
			caption: icaption,
			//type:"post",
			editurl: iediturl,
			/*onSelectRow: function (id) {
				alert(id);
			},*/
			//reloadAfterSubmit:true,
			ondblClickRow: function(rowid, ri, ci) {
				//alert(rowid + " - " + ri + " - " + ci)
	            var p = $(this)[0].p;
	            if (p.selrow !== rowid) {
	            	// prevent the row from be unselected on double-click
	                // the implementation is for "multiselect:false" which we use,
	                // but one can easy modify the code for "multiselect:true"
	            	$(this).jqGrid('setSelection', rowid);
	            }
	            $(this).jqGrid('editGridRow', rowid,  editSettings());
	        }
		}
	},

	modalDelete:function(iafterSubmit) {
		return {// options for the Delete Dailog
			/*beforeShowForm: function ($form) {
			    $("td.delmsg", $form[0]).html("Do you really want delete the row with <b>id=" +
			         $(this).jqGrid('getGridParam','selrow') + "</b>?");
			},*/
			onclickSubmit : function(params, posdata) {
				return {action:"iud",iud:"d",id:posdata}
			},
			afterSubmit : iafterSubmit,
			errorTextFormat: common.errorTextFormat};
	},

	modalCreate:function(iwidth, iafterSubmit) {
		return{// options for the Add Dialog
			width:iwidth,
			bottominfo: common.fieldsRequiredText,
			closeAfterAdd: true,
			recreateForm: true,
			editData: {action:"iud",iud:"i"},
			afterSubmit: iafterSubmit,
			errorTextFormat: common.errorTextFormat};
	},

	modalEdit:function(iwidth, ibottominfo, iafterSubmit) {// options for the Edit Dialog
		return {
			width:iwidth,
			bottominfo: common.fieldsRequiredText+ibottominfo,
			recreateForm: true,
			closeAfterEdit: true,
			editData: {action:"iud",iud:"u"},
			afterSubmit: iafterSubmit,
			errorTextFormat: common.errorTextFormat};
	},

	showFilterOptions:{
		// JSON stringify all data from search, including search toolbar operators
		stringResult: true,
		// instuct the grid toolbar to show the search options
		searchOperators: true
	},

	idTemplate: function(iname, irowpos, icolpos) {
    	return {name: iname, key: true, hidden:true, editable: true,
    		editrules: { edithidden: false }, hidedlg: true,
    		formoptions: { rowpos: irowpos, colpos: icolpos}};
	},

    passwordTemplate: function(iname, irowpos, icolpos) {
        return {name: iname, hidden:true, edittype:"password",
		editable: true, editrules: { edithidden: true},
		/*editrules: { required:true },*/
		formoptions: { rowpos: irowpos, colpos: icolpos}};
    },

    textTemplate: function(iname, iwidth, irequired, iprefix, ieditable, irowpos, icolpos) {
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editOptions = {};
    	var editrules = {required: irequired, edithidden: true}
    	return generateFieldTemplate(iname, iwidth, ieditable, "text", editrules,
    			textSearchOptions, formoptions, {}, 'text');
    },

    phoneTemplate: function(iname, iwidth, irequired, iprefix, ieditable, irowpos, icolpos) {
    	var editOptions = {dataInit: function (elem) { $(elem).mask("(999) 999-9999"); }};
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editrules = {required: irequired}
    	return generateFieldTemplate(iname, iwidth, ieditable, "text", editrules,
    			textSearchOptions, formoptions, editOptions, 'text');
    },

    selectTemplate: function(iname, iwidth, irequired, iprefix, itype, editvalue,
    		searchvalue, ieditable, irowpos, icolpos) {
    	var searchOptions = {sopt: ['eq', 'ne'], value: searchvalue};
    	var editOptions = {value: editvalue};
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editrules = {required: irequired, edithidden: true}
    	return generateFieldTemplate(iname, iwidth, ieditable, itype, editrules,
    			searchOptions, formoptions, editOptions, 'select');
    },

    dateTemplate: function(iname, iwidth, irequired, iprefix, irowpos, icolpos) {
    	 var searchOptions = { sopt: numberSearchOptions,
			dataInit:  function (elem) {
		        setTimeout(function () {
		            $(elem).datepicker({
		                dateFormat: "mm/dd/yy",
		                autoSize: true,
		                changeYear: true,
		                changeMonth: true,
		                showButtonPanel: true
		            });
		        }, 100);
		}};

    	var editOptions = {dataInit: function (elem) {
	        $(elem).datepicker({
	            dateFormat: 'mm/dd/yy',
	            autoSize: true,
	            changeYear: true,
	            changeMonth: true,
	            showButtonPanel: true,
	            showWeek: false,
	            minDate: new Date(2013, 0, 1),
	            maxDate: new Date(2020, 0, 1),
	            showOn: 'focus'
	        });
	    }};
    	var formoptions = {srcformat:'mm/dd/YY', newformat:'ShortDate',
    			elmprefix: " * ", rowpos: irowpos, colpos: icolpos};
    	var editrules = {required: irequired}
    	var dateOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules,
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( dateOptions, generateOptions );
    },

    numberTemplate: function(iname, iwidth, irequired, iprefix, irowpos, icolpos) {
    	var searchOptions = { sopt: numberSearchOptions};
    	var editrules = {number:true, required: irequired}
    	var editOptions = {};
    	var formoptions = {elmprefix: " * ", rowpos: irowpos, colpos: icolpos};
    	var numberOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules,
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( numberOptions, generateOptions );
    },

    textAreaTemplate: function(iname, iwidth, irequired, iprefix, ieditable, irowpos, icolpos) {
    	var searchOptions = {};
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editOptions = {rows:"2",cols:"23"};
    	var textAreaOptions = {wrap:"on", hidden:true};
    	var editrules = {edithidden: true, required: irequired}
    	var generateOptions = generateFieldTemplate(iname, iwidth, ieditable, "textarea", editrules,
    			searchOptions, formoptions, editOptions, 'text');
    	return $.extend( textAreaOptions, generateOptions );
    },

    ajaxCall: function(async, type, url, data, successCallback, errorCallback) {
		$.ajax({
			type: type,
			url: url,
			async: async,
			data: data,
			success: successCallback,
			error: errorCallback
		});
	},

	errorAlert:function(event,msg){
		alert(msg);
		event.preventDefault();
	},

	errorSpan:function(event,spanid, msg){
		$(spanid).html(msg);
		event.preventDefault();
	},

	afterSubmit: function(response) {
		debugger;
		var res = common.decode(response.responseText)
		if (res['error']) {
			return [false, 'Error: ' + res['message']];
		} else {
			debugger;
			fetchGridData();
			return [true];
		}

		/*var myInfo = '<div class="ui-state-highlight ui-corner-all">'+
         			 	'<span class="ui-icon ui-icon-info" ' +
         			 	'style="float: left; margin-right: .3em;"></span>' +
         			 	response.responseText +
         			 	'</div>',
	    $infoTr = $("#TblGrid_" + $.jgrid.jqID(this.id) + ">tbody>tr.tinfo"),
	    $infoTd = $infoTr.children("td.topinfo");
	    $infoTd.html(myInfo);
	    $infoTr.show();

	    // display status message to 3 sec only
	    setTimeout(function () {
	        $infoTr.slideUp("slow");
	    }, 3000);

	    return [true, "", ""];*/
	},

	errorTextFormat: function (data) {
		return 'Error: ' + data.responseText;
		//return '<span class="ui-icon ui-icon-alert" ' +
        // 		'style="float:left; margin-right:.3em;"></span>' +
        // 		data.responseText;
	},

	fieldsRequiredText: "Fields marked with * are required",

	setGridData: function(gridid, type, url, params, successCallback) {
		var gridArrayData = [];
		$(gridid)[0].grid.beginReq();
		common.ajaxCall(true, type, url, params,
			function( response ) {
				var res = common.decode(response);
				if (res['error']) {
					common.errorAlert(event, res['message']);
				}else if (res['success']) {
					gridArrayData = successCallback(res['data']);
					// set the new data
					$(gridid).jqGrid('setGridParam', { data: gridArrayData});
					// hide the show message
					$(gridid)[0].grid.endReq();
					// refresh the grid
					$(gridid).trigger('reloadGrid');
				}
			},
			function( response ) {
				common.errorAlert(event, response);
			}
		)
	}
};

var common = Common;

function generateFieldTemplate(iname, iwidth, ieditable, iedittype, ieditrules,
		isearchoptions, iformoptions, ieditoptions, istype) {
	return {name: iname,
	width: iwidth,
	editable: ieditable,
	edittype: iedittype,
	editrules: ieditrules,
	searchoptions : isearchoptions,
	formoptions: iformoptions,
	editoptions: ieditoptions,
	stype: istype};
}
