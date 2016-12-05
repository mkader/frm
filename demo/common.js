var textSearchOptions = {sopt:['cn','eq', 'ne', 'bw', 'bn', 'ew', 'en',  'nc']};
var numberSearchOptions = ['eq','ne','lt','le','gt','ge'];
var gridFooterIcons = {search: false, view: true, add: true, edit: true, del: true, refresh: true};
var Common = {

	autoComplete:function(fldName, iurl, idataType){
		$(fldName ).autocomplete({
			source: function( request, response ) {
				var regex = new RegExp(request.term, 'i');
				common.ajaxCall(false, "get", iurl, null,
					function( data ) {
						response($.map(data, function(item) {
							if(regex.test(item)) return item;
			            }));
					},
					function( data ) {
						common.errorAlert(data.responseText);
					}
				)
			},

		    minLength: 1,
		    select: function( event, ui ) {
		    	return ui.item;
		      /*log( ui.item ?
		        "Selected: " + ui.item.label :
		        "Nothing selected, input was " + this.value);*/
		    },
		    open: function() {
		      $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		    },
		    close: function() {
		      $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		    }
		});
		$(".ui-autocomplete").css("zIndex", parseInt($("[style*=z-index]:last").css("zIndex"), 10) + 2);
	},

	decimalOnly: function(fldName) {
		$(fldName).keypress(function (event) {
		    if ((event.which != 46 || $(this).val().indexOf('.') != -1)
		    	&& (event.which < 48 || event.which > 57)) {
		        event.preventDefault();
		    }

		    var text = $(this).val();

		    if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 2)) {
		        event.preventDefault();
		    }
		});
	},

	numberOnly: function(fldName) {
		$(fldName).keypress(function (event) {
			if (event.which < 48 || event.which > 57) event.preventDefault();
		});
	},

	jsonParse:function(a){
		return JSON.parse(a)
	},

	validateEmail: function(email) {
	    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	},

	//There is a bug in editSettings, fix it later.
	onDblClickRow: function(rowid, ri, ci) {
		//alert(rowid + " - " + ri + " - " + ci)
        var p = $(this)[0].p;
        if (p.selrow !== rowid) {
        	// prevent the row from be unselected on double-click
            // the implementation is for "multiselect:false" which we use,
            // but one can easy modify the code for "multiselect:true"
        	$(this).jqGrid('setSelection', rowid);
        }
        $(this).jqGrid('editGridRow', rowid,  editSettings());
    },

	gridOptions:function(ipager, icolModel, icaption, iediturl, iwidth, ionSelectRow,
			igridComplete, irowNum, iheight, iondblClickRow) {
		//if(typeof(iwidth)==='undefined') iwidth = 900;
		return {colModel: icolModel,
			/*loadComplete: function () {
			    $(this).find(">tbody>tr.jqgrow:visible:odd").addClass("altRow");
			},*/
			viewrecords: true,
	        //autowidth: true,
	        height: "auto",
	        width: iwidth,
			height: iheight,
			rowNum: irowNum,
			ignoreCase: true,
			datatype: 'local',
			multiSort: true,
			//toppager: true,
            altRows: true,
			rownumbers: true,
	        rownumWidth: 25,
	        rowList: [irowNum,irowNum*2,irowNum*4],
			pager: ipager,
			mtype:"POST",
			caption: icaption,
			//type:"post",
			editurl: iediturl,
			onSelectRow:ionSelectRow,
			gridComplete:igridComplete,
			ondblClickRow: iondblClickRow,
		}
	},

	modalDelete:function(iafterSubmit) {
		var d = (new Date()).getTime();
		return {// options for the Delete Dailog
			/*beforeShowForm: function ($form) {
			    $("td.delmsg", $form[0]).html("Do you really want delete the row with <b>id=" +
			         $(this).jqGrid('getGridParam','selrow') + "</b>?");
			},*/
			onclickSubmit : function(params, posdata) {
				return {action:"iud",iud:"d",id:posdata,nocache:d}
			},
			afterSubmit : iafterSubmit,
			errorTextFormat: common.errorTextFormat};
	},

	modalCreate:function(iwidth, iafterSubmit, ibeforeShowForm) {
		var d = (new Date()).getTime();
		return{// options for the Add Dialog
			width:iwidth,
			modal:true,
			jqModal:true,
			bottominfo: common.fieldsRequiredText,
			closeAfterAdd: true,
			beforeShowForm: ibeforeShowForm,
			recreateForm: true,
			editData: {action:"iud",iud:"i",nocache:d},
			afterSubmit: iafterSubmit,
			errorTextFormat: common.errorTextFormat};
	},

	modalEdit:function(iwidth, ibottominfo, iafterSubmit, ibeforeShowForm) {// options for the Edit Dialog
		var d = (new Date()).getTime();
		return {
			width:iwidth,
			bottominfo: common.fieldsRequiredText+ibottominfo,
			recreateForm: true,
			closeAfterEdit: true,
			beforeShowForm: ibeforeShowForm,
			editData: {action:"iud",iud:"u",nocache:d},
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
    		searchvalue, ieditable, irowpos, icolpos, idefaultvalue) {
    	var searchOptions = {};
    	var editOptions = {};
		var searchOptions = {sopt: ['eq', 'ne'], value: searchvalue};
		var editOptions = {value: editvalue, defaultValue: idefaultvalue };
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editrules = {required: irequired, edithidden: true};
    	var formatter = {formatter:{}};
    	if (itype=='checkbox') formatter = {formatter:itype};
    	var generateOptions = generateFieldTemplate(iname, iwidth, ieditable, itype, editrules,
    			searchOptions, formoptions, editOptions, 'select');
    	return $.extend( formatter, generateOptions );
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
    			elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editrules = {required: irequired}
    	var dateOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules,
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( dateOptions, generateOptions );
    },

    numberTemplate: function(iname, iwidth, irequired, iprefix, irowpos, icolpos, ieditOptions) {
    	//formatter:'currency', formatoptions:{decimalSeparator:",", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}
    	var searchOptions = { sopt: numberSearchOptions};
    	var editrules = {number:true, required: irequired}
    	var editOptions = ieditOptions;
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var numberOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules,
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( numberOptions, generateOptions );
    },

    textAreaTemplate: function(iname, iwidth, irequired, iprefix, ieditable, irowpos, icolpos, irows, icols) {
    	var searchOptions = {};
    	var formoptions = {elmprefix: iprefix, rowpos: irowpos, colpos: icolpos};
    	var editOptions = {rows:irows,cols:icols};
    	var textAreaOptions = {wrap:"on", hidden:false};
    	var editrules = {edithidden: false, required: irequired}
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

	errorAlert:function(msg){
		alert(msg);
	},

	errorSpan:function(event,spanid, msg){
		$(spanid).html(msg);
		event.preventDefault();
	},

	afterSubmit: function(response) {
		var res = common.jsonParse(response.responseText)
		if (res['error']) {
			return [false, 'Error: ' + res['message']];
		} else {
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
				var res = common.jsonParse(response);
				if (res['error']) {
					common.errorAlert(res['message']);
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
				common.errorAlert(response);
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
