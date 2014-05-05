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
		
		loadcontent("#context", "ajax/" + hash.replace( /^#/, '' ) + ".html");
	});
	$(window).hashchange();
});

function loadcontent( id, path )
{
	$( "#loading" ).removeClass("hidden"); //show loading animation
	
	$( id ).load( path, function( response, status, xhr ) {
	if ( status == "error" ) {
		var msg = "<h1>Sorry :(</h1><br>Error Occured:<br>";
		$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
	}
	if($( "#error" ).html() == '' || $( "#error" ).html() == undefined)
		$( "#error" ).addClass("hidden");
	else
		$( "#error" ).removeClass("hidden");
	});
	
	$( "#loading" ).addClass("hidden"); //hide loading animation
}

