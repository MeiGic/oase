$( document ).ready(function() {
	loadcontent("#header", "ajax/header.html");
	loadcontent("#footer", "ajax/footer.html");
	var spinner = new Spinner().spin();
	loading.appendChild(spinner.el);
	
	$(window).hashchange( function() {
		if(window.location.hash == '' || window.location.hash == undefined)
			window.location.hash = 'news';
		
		var hash = window.location.hash;
		hash = ( hash.replace( /^#/, '' ) || 'blank' )
		
		$( "#error" ).html('');
		$( "#context" ).html('');
		
		loadcontent("#context", "ajax/" + hash + ".html");
	});
	$(window).hashchange();
});

function centerContext() {
	var offset = ($( window ).height() - $( "body" ).height()) / 6;
	$( "#context" ).css("margin-top", 0);
	$( "#context" ).flexVerticalCenter({
		verticalOffset : offset
	});
}

function loadcontent( id, path, completeEvent )
{
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