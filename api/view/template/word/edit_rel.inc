<p>Add related word</p>
<style>
.ui-autocomplete-loading {
	background: white url('images/ui-anim_basic_16x16.gif') right center
		no-repeat;
}
</style>
<script>
	$(function() {
		function log( message ) {
			$( "<div>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
 
		$( "#word" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "<?php echo core::constructURL("word", "search", array("suggest"), "json"); ?>",
					type: "post",
					dataType: "json",
					data: {
    					term: request.term
    				},
					success: function( data ) {
						response( $.map( data.words, function( item ) {
							return {
								label: item.name + ': ' + item.label,
								value: item.name
							}
						}));
					}
				});
			},
			minLength: 2,
		});
	});
</script>
<div class="ui-widget">
	<label for="word">Word: </label> <input id="word" />
</div>
