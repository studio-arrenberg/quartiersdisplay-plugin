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
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'nachrichten',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'posts_per_page' => $NUM_POSTS * 0.5,
    'date_query' => array(
        'after' => date('Y-m-d', strtotime('-2 months')) 
    ),
);
# Query 10 umfragen chronologically not older than 1 week
$args3 = array(
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
    'orderby' => 'rand',
    'post_type' => 'projekte',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'posts_per_page' => $NUM_POSTS > 2 ? $NUM_POSTS : 2,    
);
# Get latest projekte
$args5 = array(
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'projekte',
    'post_status' => 'publish',
    'suppress_filters' => true,
    'posts_per_page' => 1,
);
# check if $args5 is already in $args4
if (in_array($args5, $args4)) {
    $args5 = array();
}

# Additional Posts
$additional_post = array();
# Energie Ampel
# Add Energie Ampel information to additional_post

# Get data from http://api.energiewetter.de/
$energie_wetter = file_get_contents('http://api.energiewetter.de/');
$energie_wetter = json_decode($energie_wetter, true);
# get the next phase from forecast which is not the current phase
$next_phase = null;
# iterate through forecast
foreach ($energie_wetter['forecast'] as $key => $item) {
    if ($item['color'] != $energie_wetter['current']['color'] && $next_phase == null) {
        // $next_phase = $key;
        // $next_phase = $item;
        $next_phase = "Ab ".date('H:i', strtotime($key))." Uhr ist ".$item['label']['plural']." Phase";
    }
}

# write to array
$additional_post[] = array(
    'id' => 'energie_wetter',
    'title' => 'Energie Wetter für Wuppertal',
    'subtitle' => 'Phase',
    'content' => 'Aktueller Emissionsfaktor '.$energie_wetter['current']['emissions']['amount'].' gramm CO2 pro kWh',
    'type' => 'energie_wetter',
    'text' => $next_phase,
);
# Promote Quartiersplattform with Name and Link
$additional_post[] = array(
    'id' => 'quartiersplattform',
    'title' => get_field('quartiersplattform-name', 'option'),
    'subtitle' => home_url(),
    'content' => 'Hier geht es zur Quartiersplattform',
    'type' => 'info',
    'text' => 'Hier geht es zur Quartiersplattform',
);
# Promote Aufbruch am Arrenberg
get_field('quartiersdisplays_office', 'option', false) && 
$additional_post[] = array(
    'id' => 'office',
    'title' => get_field('quartiersdisplays_office_title', 'option'),
    'subtitle' => get_field('quartiersdisplays_office_subtitle', 'option'),
    'type' => 'info',
    'text' => get_field('quartiersdisplays_office_text', 'option'),
);

# Merge Posts
$posts = array_merge(get_posts($args1), get_posts($args2), get_posts($args3), get_posts($args4), get_posts($args5));
# Remove from posts to fit limit NUM_POSTS
$posts = array_slice($posts, 0, $NUM_POSTS);

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

        // return projekt name
        'project' => $post->post_type == 'projekte' ? get_the_title($post->ID) : get_the_title(get_the_terms($post->ID, 'projekt')[0]->description),

        // veranstaltungen
        'event_date' => get_field('event_date', $post->ID),
        'event_time' => get_field('event_time', $post->ID),
        'event_end_time' => get_field('event_end_time', $post->ID),

        // text
        'text' => get_post_meta($post->ID)['text'][0],

        // emoji
        'emoji' => $post->post_type == 'projekte' ? get_post_meta($post->ID)['emoji'][0] : get_post_meta(get_the_terms($post->ID, 'projekt')[0]->description)['emoji'][0],

        // umfragen
        'poll' => getPollData($post->ID),

        // meta field
        // 'meta' => get_post_meta($post->ID) 

        // get taxonomies
        // 'taxonomies' => get_the_terms($post->ID, 'projekt')[0],

        // term list
        // 'term_list' => wp_get_post_terms( $post->ID, 'projekt', array( 'fields' => 'all' ) ),

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

# Combine posts and additional_post
$all_content = array_merge($content, $additional_post);
# Randomly distribute posts in array
shuffle($all_content);


# Return posts
echo json_encode(array('meta' => $meta, 'content' => $all_content));

?>