<?php

# Set Header
header('Content-Type: application/json; charset=utf-8');

# Define Meta
$meta = array('Result' => 'OK', 'Balance' => "banane");

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

# Iterate through posts and add aditional information
foreach ($posts as $post) {
    $post->id = $post->ID;
    $post->title = $post->post_title;
    $post->content = $post->post_content;
    $post->date = $post->post_date;
    $post->permalink = get_permalink($post->ID);
    $post->thumbnail = get_the_post_thumbnail_url($post->ID);
    $post->type = get_post_type($post->ID);
    $post->meta = get_post_meta($post->ID);
    if ($post->type == 'veranstaltungen') {
        $post->date = $post->meta['event_date'][0];
        $post->time = $post->meta['event_time'][0];
        $post->location = $post->meta['event_location'][0];
    }
    if ($post->type == 'umfragen') {
        $post->date = $post->meta['poll_date'][0];
        $post->time = $post->meta['poll_time'][0];
    }
    $post->excerpt = get_the_excerpt($post->ID);
    $post->author = get_the_author_meta('display_name', $post->post_author);
    $post->author_id = $post->post_author;
    $post->author_link = get_author_posts_url($post->post_author);
    $post->author_avatar = get_avatar_url($post->post_author);
    $post->author_name = get_the_author_meta('display_name', $post->post_author);
    $post->categories = get_the_category($post->ID);
    $post->tags = get_the_tags($post->ID);
}

# Return posts
echo json_encode(array('meta' => $meta, 'posts' => $posts));

?>