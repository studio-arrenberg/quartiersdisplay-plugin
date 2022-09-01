<?php


get_header();

echo "<br><br><br>"; // das ist ein blöder fehler
echo "<h1>Hello Display</h1>";

# Define
# Amount of posts to display
$NUM_POSTS = 10;
$REMAINING_POSTS = 0;
$posts = [];

# Query Posts
# Query 10 veranstaltugen chronologically not older than today
$args1 = array(
    'post_type'=>'veranstaltungen', 
    'post_status'=>'publish', 
    'posts_per_page'=> $NUM_POSTS,
    'offset' => '0', 
    'meta_query' => array(
        'relation' => 'AND',
        'date_clause' => array(
            'key' => 'event_date',
            'value' => date("Y-m-d"),
            'compare'	=> '>=',
            'type' => 'DATE'
        ),
        'time_clause' => array(
            'key' => 'event_time',
            'compare'	=> '=',
        ),
    ),
    'orderby' => array(
        'date_clause' => 'ASC',
        'time_clause' => 'ASC',
    )
);
# Query 10 nachrichten chronologically
$args2 = array(
    'posts_per_page' => $NUM_POSTS,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'nachrichten',
    'post_status' => 'publish',
    'suppress_filters' => true
);
# Query 10 umfragen chronologically not older than 1 week
$args3 = array(
    'posts_per_page' => $NUM_POSTS,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'umfragen',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'date_query' => array(
        'after' => date('Y-m-d', strtotime('-10 days')) 
    )
);
# Query random projekte
$args4 = array(
    'posts_per_page' => $NUM_POSTS,
    'orderby' => 'rand',
    'post_type' => 'projekte',
    'post_status' => 'publish',
    'suppress_filters' => true
);

# Display veranstaltung posts with template
$posts = get_posts($args1);
$REMAINING_POSTS = $NUM_POSTS - count($posts);
if ($REMAINING_POSTS > 0) {
    $posts = array_merge($posts, get_posts($args2));
    $REMAINING_POSTS = $NUM_POSTS - count($posts);
    if ($REMAINING_POSTS > 0) {
        $posts = array_merge($posts, get_posts($args3));
        $REMAINING_POSTS = $NUM_POSTS - count($posts);
        if ($REMAINING_POSTS > 0) {
            $posts = array_merge($posts, get_posts($args4));
        }
    }
}

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