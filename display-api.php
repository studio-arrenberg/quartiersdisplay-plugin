<?php

# Set Header
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:3000'); // for local development

# Define
# Amount of posts to display
$NUM_POSTS = get_field('quartiersdisplays_slides', 'option') ? get_field('quartiersdisplays_slides', 'option') : 10;
$SLIDE_DURATION = get_field('quartiersdisplays_slide_duration', 'option') ? get_field('quartiersdisplays_slide_duration', 'option') * 1000 : 8000;
$REMAINING_POSTS = 0;
$posts = [];

# Define Meta
$meta = array(
    'name' => get_field('quartiersplattform-name', 'option'),
    'slide_duration' => $SLIDE_DURATION,
);

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
    ),
    'posts_per_page' => $NUM_POSTS * 0.5,
);
# Query 10 nachrichten chronologically
$args2 = array(
    'posts_per_page' => $NUM_POSTS,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'nachrichten',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'posts_per_page' => $NUM_POSTS * 0.5,
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
    ),
    'posts_per_page' => $NUM_POSTS,
);
# Query random projekte
$args4 = array(
    'posts_per_page' => $NUM_POSTS,
    'orderby' => 'rand',
    'post_type' => 'projekte',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'posts_per_page' => $NUM_POSTS,
);

# Merge Posts
$posts = array_merge(get_posts($args1), get_posts($args2), get_posts($args3), get_posts($args4));
# Remove from posts to fit limit NUM_POSTS
$posts = array_slice($posts, 0, $NUM_POSTS);
# Randomly distribute posts in array
shuffle($posts);

# clean array
$content = array();
foreach ($posts as $post) {
    $content[] = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'subtitle' => get_post_meta($post->ID)['slogan'][0],
        'content' => $post->post_content,
        'date' => $post->post_date,
        'image' => get_the_post_thumbnail_url($post->ID, 'medium'),
        'type' => $post->post_type,
        'author' => get_the_author_meta('display_name', $post->post_author),

        // veranstaltungen
        'event_date' => get_field('event_date', $post->ID),
        'event_time' => get_field('event_time', $post->ID),
        'event_end_time' => get_field('event_end_time', $post->ID),

        // text
        'text' => get_post_meta($post->ID)['text'][0],

        // projekte
        'emoji' => get_post_meta($post->ID)['emoji'][0],

        // umfragen
        'poll' => getPollData($post->ID)

        // meta field
        // 'meta' => get_post_meta($post->ID) 
    );
}

# Formate Poll Data
function getPollData($id) {
    if (!$id) return;
    $array = get_post_meta($id, 'polls', true);
    if (!$array) return;
    // remove user from array
    foreach ($array as $key => $value) {
        unset($array[$key]['user']);
    }

    // format array
    $formatted = array();
    foreach ($array as $key => $value) {
        $formatted[] = array(
            'title' => $value['field'],
            'votes' => $value['count'],
            'percentage' => $value['percentage'],
            'total-votes' => $value['total_voter'],
        );
    }

    return $formatted;
}

# Return posts
echo json_encode(array('meta' => $meta, 'content' => $content));

?>