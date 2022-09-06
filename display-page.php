<?php


get_header();

echo "<br><br><br>"; // das ist ein blöder fehler
echo "<h1>Hello Display</h1>";

# Shortcut
$easy_query = array(
    'post_type'=> array('veranstaltungen', 'nachrichten', 'projekte', 'umfragen'), 
    'post_status'=>'publish', 
    'posts_per_page'=> 20,
    'orderby' => 'date'
);
# Display Easy
card_list($easy_query);  // Using QP Card List Funktion

get_footer();

?>