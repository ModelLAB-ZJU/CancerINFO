// JavaScript Document

$(document).ready(function(){
	"use strict";
    var displayed_version = '';
    var available_versions = {};
    var location_ = window.location.href;

    loadVersions(function() {
      tree.init(displayed_version);
      initEvents();
      OutJS.backToTop();
      initQtips();
    });

    function loadVersions(callback) {
      $.get('api/versions')
        .done(function(data) {
          if (data instanceof Array) { 
            available_versions = data.reduce(
              function(acc, cur) {
                acc[cur.api_identifier] = cur;
                return acc;
              },
              {}
            );
          }
        })
        .fail(function() {
          // Error handling
        }).always(function() {
        if (callback instanceof Function) {
          callback();
        }
      })
    }

    function initEvents() {
    	$('#tumor_search button').click(function() {
	        OutJS.search();
	    });

	    $("#tumor_search input").keyup(function () {
	    	var _content = $(this).val();
	    	if(_content.length > 0) {
		  		$("#searchRemoveIcon").show();
	    	}else {
	    		$("#searchRemoveIcon").hide();
	    		OutJS.search();
	    		OutJS.backToTop();
	    	}
		});
    }


    function initQtips() {
    	$('#expand-nodes-btn').qtip({
	        content:{text: "Expand all branches"},
	        style: { classes: 'qtip-light qtip-rounded qtip-shadow qtip-grey' },
            show: {event: "mouseover"},
            hide: {event: "mouseout"},
            position: {my:'bottom left',at:'top center', viewport: $(window)}
	    });

	    $('#collapse-nodes-btn').qtip({
	        content:{text: "Collapse all branches"},
	        style: { classes: 'qtip-light qtip-rounded qtip-shadow qtip-grey' },
            show: {event: "mouseover"},
            hide: {event: "mouseout"},
            position: {my:'bottom left',at:'top center', viewport: $(window)}
	    });
    }
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        OutJS.search();
        OutJS.backToTop();
    }
});

var OutJS = (function() {
	"use strict";

	function search() {
		var searchKeywards = $('#tumor_search input').val().toLowerCase(),
			result = tree.search(searchKeywards),
			resutlLength = result.length,
			infoText = (resutlLength === 0 ? "No" : resutlLength) + " result" + (resutlLength <= 1 ? "" :"s" );

		$("#searchResult").hide();
		$("#searchResult").css('z-index', 1);

		if(searchKeywards.length > 0) {
			$("#searchResult").text(infoText);
			$("#searchResult").css('z-index', 2);
        	$("#searchResult").show();
	    }
	    result = null;
	}

	function backToTop() {
        // TODO this never really hides itself since we removed .affix({ offset: {top:100} });
		if ( ($(window).height() + 100) < $(document).height() ||
				($(window).width() + 50) < $(document).width() ) {
		    $('#top-link-block').removeClass('hidden');
		    $('#top-link-block').addClass('fixed-bottom');
		} else {
		    $('#top-link-block').removeClass('fixed-bottom');
			$('#top-link-block').addClass('hidden');
		}
	}
	return {
		search: search,
		backToTop: backToTop
	};
})();
