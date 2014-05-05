$( document ).ready(function() {
	loadcontent("#header", "ajax/header.html");
	loadcontent("#footer", "ajax/footer.html");
	
	if(window.location.hash == '' || window.location.hash == undefined)
		window.location.hash = 'news';
	
	$(window).hashchange( function() {
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
}

