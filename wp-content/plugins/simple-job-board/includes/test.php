
<?php 
function my_shortcode(){
 	$message  = "<h3>hello world</h3>";
	return #message;
}

add_shortcode('greeting', 'my_shortcode');

?>
