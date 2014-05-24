var currenthash, currentpara;

$( document ).ready(function() {
	ajaxload("#header", "ajax/header.html");
	ajaxload("#footer", "ajax/footer.html");
	var spinner = new Spinner().spin();
	loading.appendChild(spinner.el);
	
	updatehash();
	loadpage();
	if(currentpara.length > 1)
		loadelse();
	
	$(window).hashchange( function() {
		//console.log("hashchange");
		var temphash = window.location.hash;
		temphash = ( temphash.replace( /^#/, '' ) || 'blank' );
		temppara = temphash.split('&');
		
		if(temppara[0] != currentpara[0]) {
			updatehash();
			loadpage();
		} else {
			updatehash();		
			loadelse();
		}
	});
});

function updatehash() {
	//console.log("updatehash");
	if(window.location.hash == undefined || window.location.hash == '')
		window.location.hash = 'news';
	
	currenthash = window.location.hash;
	currenthash = ( currenthash.replace( /^#/, '' ) || 'blank' );
	currentpara = currenthash.split('&');
}

function loadpage() {
	//console.log("loadpage");
	var page = currentpara[0];
	$( "#error" ).html('');
	$( "#context" ).html('');
	ajaxload("#context", "ajax/" + page + ".html");
}

function ajaxload( id, path, completeEvent )
{
	//console.log("ajaxload:" + id);
	var animatePeriod = 200;
	
	$( id ).clearQueue();
    $( id ).stop();

	$( "#loading" ).animate({opacity: 1}, animatePeriod);
	$( "#loading" ).removeClass("hidden"); //show loading animation
	
	$( id ).animate({opacity: 0}, animatePeriod, function() {
		// Animation complete.
		$( id ).load( path, function( response, status, xhr ) {
			if ( status == "error" ) {
				var msg = "<h1>Sorry :(</h1><br>Error Occured:<br>";
				$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			}
			else
				$( id ).animate({opacity: 1}, animatePeriod);
			
			if($( "#error" ).html() == '' || $( "#error" ).html() == undefined)
			{
				$( "#error" ).animate({opacity: 0}, animatePeriod);
				$( "#error" ).addClass("hidden");
			}
			else
			{
				$( "#error" ).animate({opacity: 1}, animatePeriod);
				$( "#error" ).removeClass("hidden");
			}

			$( "#loading" ).animate({opacity: 0}, animatePeriod);
			$( "#loading" ).addClass("hidden"); //hide loading animation
		});
	});
}