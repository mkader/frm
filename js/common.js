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
	
	gridOptions:function(userColModel, caption, editurl) {
		return {colModel: userColModel,
		/*loadComplete: function () {
		    $(this).find(">tbody>tr.jqgrow:visible:odd").addClass("altRow");
		},*/
		viewrecords: true,
        //autowidth: true,
        height: "auto",
        width: 900,
		height: 230,
		rowNum: 10,
		ignoreCase: true,
		datatype: 'local',
		multiSort: true,
		altRows: true,
		rownumbers: true,
        rownumWidth: 25,
        rowList: [10,20,40],
		pager: "#jqGridPager",
		mtype:"POST",
		caption: caption,
		//type:"post",
		editurl: editurl,
		//reloadAfterSubmit:true,
		}
	},
	
	modalDelete:function() {
		return {// options for the Delete Dailog
			/*beforeShowForm: function ($form) {
			    $("td.delmsg", $form[0]).html("Do you really want delete the row with <b>id=" +
			         $(this).jqGrid('getGridParam','selrow') + "</b>?");
			},*/
			onclickSubmit : function(params, posdata) {
				return {action:"iud",iud:"d",id:posdata}
			},
			afterSubmit : common.afterSubmit,
			errorTextFormat: common.errorTextFormat};
	},
	
	modalCreate:function(iwidth) {
		return{// options for the Add Dialog
			width:iwidth,
			bottominfo: common.fieldsRequiredText,
			closeAfterAdd: true,
			recreateForm: true,
			editData: {action:"iud",iud:"i"},
			afterSubmit: common.afterSubmit,
			errorTextFormat: common.errorTextFormat};
	},
	
	modalEdit:function(iwidth, ibottominfo) {// options for the Edit Dialog
		return {
			width:iwidth,
			bottominfo: common.fieldsRequiredText+ibottominfo,
			recreateForm: true,
			closeAfterEdit: true,
			editData: {action:"iud",iud:"u"},
			afterSubmit: common.afterSubmit,
			errorTextFormat: common.errorTextFormat};
	},
	
	showFilterOptions:{
		// JSON stringify all data from search, including search toolbar operators
		stringResult: true,
		// instuct the grid toolbar to show the search options
		searchOperators: true
	},
	
	idTemplate: function(iname) {
    	return {name: iname, key: true, hidden:true, editable: true, 
    		editrules: { edithidden: false }, hidedlg: true};
	},
	
    passwordTemplate: function(iname) {
        return {name: iname, hidden:true, edittype:"password", 
		editable: true, editrules: { edithidden: true}
		/*editrules: { required:true },
		formoptions: {elmprefix: " * "}*/}
    },

    textTemplate: function(iname, iwidth, irequired, iprefix) {
    	var formoptions = {elmprefix: iprefix};
    	var editOptions = {};
    	var editrules = {required: irequired}
    	return generateFieldTemplate(iname, iwidth, true, "text", editrules, 
    			textSearchOptions, formoptions, {}, 'text');
    },
    
    phoneTemplate: function(iname, iwidth, irequired, iprefix) {
    	var editOptions = {dataInit: function (elem) { $(elem).mask("(999) 999-9999"); }};
    	var formoptions = {elmprefix: iprefix};
    	var editrules = {required: irequired}
    	return generateFieldTemplate(iname, iwidth, true, "text", editrules, 
    			textSearchOptions, formoptions, editOptions, 'text');
    },
    
    selectTemplate: function(iname, iwidth, irequired, iprefix, itype, editvalue, searchvalue) {
    	var searchOptions = {sopt: ['eq', 'ne'], value: searchvalue};
    	var editOptions = {value: editvalue};
    	var formoptions = {elmprefix: iprefix};
    	var editrules = {required: irequired}
    	return generateFieldTemplate(iname, iwidth, true, itype, editrules, 
    			searchOptions, formoptions, editOptions, itype);
    },
    
    dateTemplate: function(iname, iwidth, irequired, iprefix) {
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
    	var formoptions = {srcformat:'mm/dd/YY', newformat:'ShortDate', elmprefix: " * "};
    	var editrules = {required: irequired}
    	var dateOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules, 
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( dateOptions, generateOptions );
    },
     
    numberTemplate: function(iname, iwidth, irequired, iprefix) {
    	var searchOptions = { sopt: numberSearchOptions};
    	var editrules = {number:true, required: irequired}
    	var editOptions = {};
    	var formoptions = {elmprefix: " * "};
    	var numberOptions = {align:'right'};
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "text", editrules, 
				searchOptions, formoptions, editOptions, 'text');
    	return $.extend( numberOptions, generateOptions );
    },     
    
    textAreaTemplate: function(iname, iwidth, irequired, iprefix) {
    	var searchOptions = {};
    	var formoptions = {elmprefix: iprefix};
    	var editOptions = {rows:"2",cols:"23"};
    	var textAreaOptions = {wrap:"on", hidden:true};
    	var editrules = {edithidden: true, required: irequired}
    	var generateOptions = generateFieldTemplate(iname, iwidth, true, "textarea", editrules, 
    			searchOptions, formoptions, editOptions, 'text');
    	return $.extend( textAreaOptions, generateOptions );
    },
   
    ajaxCall: function(type, url, data, successCallback, errorCallback) {
		$.ajax({
			type: type,
			url: url,
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
		var res = common.decode(response.responseText)
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
	
	setGridData: function(type, url, params, successCallback) {
		var gridArrayData = [];
		$("#jqGrid")[0].grid.beginReq();
		common.ajaxCall(type, url, params,
			function( response ) {
				var res = common.decode(response);
				if (res['error']) {
					common.errorAlert(event, res['message']);
				}else if (res['success']) {
					gridArrayData = successCallback(res['data']);
					// set the new data
					$("#jqGrid").jqGrid('setGridParam', { data: gridArrayData});
					// hide the show message
					$("#jqGrid")[0].grid.endReq();
					// refresh the grid
					$("#jqGrid").trigger('reloadGrid');
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
