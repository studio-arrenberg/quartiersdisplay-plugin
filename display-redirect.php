<?php


# get url parameter id
$id = $_GET['id'];

# display $id
echo "ID:".$id."<br>";

# add to conter
if ($id) echo custom_slug_counter('display_'.$id, true);

# redirect to ./quartiersdisplay
// wp_redirect( home_url()."/quartiersdisplay" ); 
wp_redirect( home_url()); 
exit;

?>