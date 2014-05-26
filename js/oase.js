var currenthash, currentpara;
var animatePeriod = 200;
var sessionid;

$( document ).ready(function() {
	ajaxload("#header", "ajax/header.html");
	ajaxload("#footer", "ajax/footer.html");
	var spinner = new Spinner().spin();
	loading.appendChild(spinner.el);
	
	updatehash();
	loadpage(function onComplete() {
		if(typeof loadelse != 'undefined')
			loadelse();
	});
	
	$(window).hashchange( function() {
		//console.log("hashchange");
		var temphash = window.location.hash;
		temphash = ( temphash.replace( /^#/, '' ) || 'blank' );
		temppara = temphash.split('&');
		
		if(temppara[0] != currentpara[0]) {
			updatehash();
			loadpage(function onComplete() {
				if(typeof loadelse != 'undefined')
					loadelse();
			});
		} else {
			updatehash();		
			loadelse();
		}
	});
});

$( document ).ajaxStart(function() {
	$( "#error" ).animate({opacity: 0}, animatePeriod);
	$( "#error" ).addClass("hidden");
});

$( document ).ajaxError(function( event, jqxhr, settings, exception ) {
	var msg = "<h1>Sorry :(</h1><br>Error Occured:<br>";
	$( "#error" ).html( msg + jqxhr.status + " " + jqxhr.statusText + "<br>" + exception);
	
	$( "#error" ).animate({opacity: 1}, animatePeriod);
	$( "#error" ).removeClass("hidden");
});

function updatehash() {
	//console.log("updatehash");
	if(typeof window.location.hash == 'undefined' || window.location.hash == '')
		window.location.hash = 'news';
	
	currenthash = window.location.hash;
	currenthash = ( currenthash.replace( /^#/, '' ) || 'blank' );
	currentpara = currenthash.split('&');
}

function loadpage(completeEvent) {
	//console.log("loadpage");
	var page = currentpara[0];
	$( "#error" ).html('');
	$( "#context" ).html('');
	ajaxload("#context", "ajax/" + page + ".html", completeEvent);
}

function ajaxload( id, path, completeEvent )
{
	//console.log("ajaxload:" + id);
	$( id ).clearQueue();
    $( id ).stop();

	$( "#loading" ).animate({opacity: 1}, animatePeriod);
	$( "#loading" ).removeClass("hidden"); //show loading animation
	
	$( id ).animate({opacity: 0}, animatePeriod, function() {
		// Animation complete.
		$( id ).load( path, function( response, status, xhr ) {
			if ( status == "success" ) {
				$( id ).animate({opacity: 1}, animatePeriod);
			}
			
			$( "#loading" ).animate({opacity: 0}, animatePeriod);
			$( "#loading" ).addClass("hidden"); //hide loading animation
			
			if(typeof completeEvent != 'undefined')
				completeEvent();
		});
	});
}