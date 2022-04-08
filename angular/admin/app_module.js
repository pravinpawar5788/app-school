"use strict";
var app=angular.module("mozzApp",['cfp.hotkeys','pubnub.angular.service']);

//////////////////////////////////////////////////////////////////////////////
//////////////////////MODULE CONFIGURATION DATA///////////////////////////////
//////////////////////////////////////////////////////////////////////////////
app.value('config',{
	ajaxModule:'../admin/',
	globalInterval:10000,	//10 seconds
	globalTimeout:10000,	//10 seconds
	pageLimit:100,
	currentPage:0,
	responseNumRows:0,
	showLog:true,
	showBtnBack:false,
	showBtnNext:false,
	btnClickedSave:false,
	btnClickedSearch:false,
	btnClickedSubmit:false,
	btnClickedProcess:false,
	sortAsc:false,
	sortedAsc:false,
	sortDesc:false,
	sortedDesc:false,
	sortKey:'',
	btnTextSave:' Save ',
	btnTextSearch:' Search ',
	btnTextSubmit:' Submit',
	btnTextProcess:' Process',
	colorWarning:'#FF7043',
	colorDanger:'#2196F3',
	colorSuccess:'#66BB6A',
	globalError:'Please try again after some time...',
	postConfig:{headers:{'Content-Type': 'application/x-www-form-urlencoded'}},
});

//////////////////////////////////////////////////////////////////////////////
//////////////////////APPLICATION DIRECTIVES/////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
app.directive('scrollToBottom', function($timeout, $window) {
    return {
        scope: {
            scrollToBottom: "="
        },
        restrict: 'A',
        link: function(scope, element, attr) {
            scope.$watchCollection('scrollToBottom', function(newVal) {
                if (newVal) {
                    $timeout(function() {
                        element[0].scrollTop =  element[0].scrollHeight;
                    }, 0);
                }

            });
        }
    };
});
app.directive('currentTime', ['$interval', 'dateFilter', function($interval, dateFilter) {

  function link(scope, element, attrs) {
    var format,
        timeoutId;

    function updateTime() {
      element.text(dateFilter(new Date(), format));
    }

    scope.$watch(attrs.currentTime, function(value) {
      format = value;
      updateTime();
    });

    element.on('$destroy', function() {
      $interval.cancel(timeoutId);
    });

    // start the UI update process; save the timeoutId for canceling
    timeoutId = $interval(function() {
      updateTime(); // update DOM
    }, 1000);
  }

  return {
    link: link
  };
}]);
//////////////////////////////////////////////////////////////////////////////
//////////////////////APPLICATION FILTERS/////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////ROUND MESSAGE
app.filter('roundMsg', function (){ 
    return function (input){
        var len=1;var devider=153;
        if(input>160){len=Math.ceil(input/devider);}
        return len;
    };
});

//////////////////////////////////////////////////////////////////////////////
//////////////////////GLOBAL FUNCTIONS & VARIABLES////////////////////////////
//////////////////////////////////////////////////////////////////////////////
app.run(function($rootScope,config){
	$rootScope.format='hh:mm:ss a';
	////////////////SYSTEM MAIN FUNCTIONS///////////////////////////////
    $rootScope.validatePagination=function (){
        var maxLimit=config.pageLimit*(config.currentPage+1);
        if(maxLimit < config.responseNumRows){
        	config.showBtnNext=true;}else{config.showBtnNext=false;}
        if(config.currentPage > 0 ){config.showBtnBack=true;}else{config.showBtnBack=false;} 
    };
    $rootScope.resetPagination=function (){
        config.currentPage=0;config.showBtnBack=false;config.showBtnNext=false; 
    };
    $rootScope.cancelTimeout=function (promise){timeout.cancel(promise);};
    $rootScope.enableButtons= function (){
        config.btnClickedSave=false;config.btnClickedSearch=false;config.btnClickedSend=false;
        config.btnClickedSendLater=false;
        config.btnTextSearch=' Search ';config.btnTextSave=' Save ';config.btnTextSend=' Send SMS ';
        config.btnTextSendLater=' Schedule SMS ';
    };
    $rootScope.disableButtons= function (){
        config.btnClickedSave=true;config.btnClickedSearch=true;config.btnClickedSend=true;
        config.btnClickedSendLater=true;
        config.btnTextSearch=' Please Wait... ';config.btnTextSave=' Please Wait... ';
        config.btnTextSend=' Please Wait... ';config.btnTextSendLater=' Please Wait... ';
    };    
    $rootScope.moveNext=function (){
        config.currentPage++;
    };
    $rootScope.moveBack=function (){
        config.currentPage--;          
    }; 
	/////////////////////////////Alerts/////////////////////////////////
    $rootScope.successAlert=function (data){
        var html='<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">'+
            '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>'+
            '<span class="text-semibold">Success! </span> '+data+'</div>';
        return html;
    };    
    $rootScope.dangerAlert=function (data){
        var html='<div class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">'+
            '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>'+
            '<span class="text-semibold">Error! </span> '+data+'</div>';
        return html;
    };    
    $rootScope.warningAlert=function (data){
        var html='<div class="alert alert-warning alert-styled-left alert-arrow-left alert-bordered">'+
            '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>'+
            '<span class="text-semibold">Warning! </span> '+data+'</div>';
        return html;
    };
    $rootScope.infoAlert=function (data){
        var html='<div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">'+
            '<button type="button" class="close" data-dismiss="alert"><span> &times; </span><span class="sr-only">Close</span></button>'+
            '<span class="text-semibold">Info! </span> '+data+'</div>';
        return html;
    };
    /////////////////////////////Sorting/////////////////////////////////
	$rootScope.processSort=function (key){
		var sort='';
		if(config.sortedAsc===true && config.sortedDesc===true){
			config.sortAsc=false;config.sortedAsc=false;config.sortDesc=false;config.sortedDesc=false;	
			$rootScope.sortKey='';
		}else{
			$rootScope.sortKey=key;
			if(config.sortAsc===false){
				config.sortAsc=true;config.sortDesc=false;config.sortedAsc=true;
				sort=key+' ASC';
				
			}else if(config.sortDesc===false){
				config.sortDesc=true;config.sortAsc=false;config.sortedDesc=true;
				sort=key+' DESC';
			}
		}
		return sort;
    };
	//////////////////block ui////////////////////////////////////
	$rootScope.blockPageUI=function(){
		$.blockUI({ 
			message: '<span class="font-weight-semibold" ><i class="icon-spinner4 spinner mr-2"></i>&nbsp; Please Wait...</span>',
			overlayCSS: {
				backgroundColor: '#000',
				// backgroundColor: '#1b2024',
				opacity: 0.9,
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				backgroundColor: 'transparent'
			}
		});
	}
	$rootScope.unblockPageUI=function(){
		$.unblockUI();	
	}
	$rootScope.blockElementUI=function(elmnt,message){
		if(message===undefined){message='Please Wait...';}
		var block = $(elmnt);		
		$(block).block({ 
			message: '<span class="font-weight-semibold" ><i class="icon-spinner4 spinner mr-2"></i>&nbsp; '+message+'</span>',
			overlayCSS: {
				backgroundColor: '#000',
				opacity: 0.7,
				cursor: 'wait'
				
			},
			// z-index for the blocking overlay 
			centerX: false, 
			css: {
				textAlign:'top', 
				border: 0,
				padding: 0,
				color: '#fff',
				backgroundColor: 'transparent'
			}
		});
	}	
	$rootScope.unblockElementUI=function(elmnt){
		var block = $(elmnt);	
		$(block).unblock();	
	}
    //////////////////////////////////////////////////////////////
	$rootScope.setSwaDefaults=function(){
		swal.setDefaults({
			buttonsStyling: true,
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light'
		});
	}
	//////////////////////////////////////////////////////////////
	$rootScope.speak=function(speech){
		window.speechSynthesis.speak(new SpeechSynthesisUtterance(speech));
	}
	//////////////////////////////////////////////////////////////
	$rootScope.resetSelect=function(){
		$('.select').select2({minimumResultsForSearch: Infinity,placeholder: 'Select an option'});
	}
	//////////////////////////////////////////////////////////////
	//convert json object array into uri string
	$rootScope.uriString=function(data){		
		var isObj = function(a) {
		  if ((!!a) && (a.constructor === Object)) {
			return true;
		  }
		  return false;
		};
		var _st = function(z, g) {
		  return "" + (g != "" ? "[" : "") + z + (g != "" ? "]" : "");
		};
		var fromObject = function(params, skipobjects, prefix) {
		  if (skipobjects === void 0) {
			skipobjects = false;
		  }
		  if (prefix === void 0) {
			prefix = "";
		  }
		  var result = "";
		  if (typeof(params) != "object") {
			return prefix + "=" + encodeURIComponent(params) + "&";
		  }
		  for (var param in params) {
			var c = "" + prefix + _st(param, prefix);
			if (isObj(params[param]) && !skipobjects) {
			  result += fromObject(params[param], false, "" + c);
			} else if (Array.isArray(params[param]) && !skipobjects) {
			  params[param].forEach(function(item, ind) {
				result += fromObject(item, false, c + "[" + ind + "]");
			  });
			} else {
				if (typeof(params[param]) == "object") {
				result += c + "=" + params[param] + "&";
				}else{
				result += c + "=" + encodeURIComponent(params[param]) + "&";
				}
			}
		  }
		  return result;
		};
		return fromObject(data);
	}
    //////////////////////////////////////////////////////////////
});

//////////////////////////////////////////////////////////////////////////////
//////////////////////END MODULE//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
