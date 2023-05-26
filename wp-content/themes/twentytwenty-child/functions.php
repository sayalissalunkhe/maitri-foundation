<?php	
							
add_action("wp_enqueue_scripts", "wp_child_theme");									
function wp_child_theme()									
{									
    if((esc_attr(get_option("wp_child_theme_setting")) != "yes")) {								
        wp_enqueue_style("parent-stylesheet", get_template_directory_uri()."/style.css");									
    }

    wp_enqueue_style("child-stylesheet", get_stylesheet_uri());		
    wp_enqueue_script("child-scripts", get_stylesheet_directory_uri() . "/js/script.js", array("jquery"), "6.1.1", true);	
}





add_action( 'init', 'custom_post_events' );
// The custom function to register a blog post type
function custom_post_events() {
    // Set the labels. This variable is used in the $args array
    $labels = array(
        'name'               => __( 'Events' ),
        'singular_name'      => __( 'Event' ),
        'add_new'            => __( 'Add Event' ),
        'add_new_item'       => __( 'Add Event' ),
        'edit_item'          => __( 'Edit Event' ),
        'new_item'           => __( 'New Event' ),
        'all_items'          => __( 'All Events' ),
        'view_item'          => __( 'View Event' ),
        'search_items'       => __( 'Search Event' ),
        'featured_image'     => 'Profile Image',
        'set_featured_image' => 'Add Profile Image'
    );
// The arguments for our post type, to be entered as parameter 2 of register_post_type()
    $args = array(
        'labels'            => $labels,
        'menu_icon'         => 'dashicons-calendar-alt',
        'description'       => 'Holds our Event post specific data',
        'public'            => true,
        'menu_position'     => 5,
        'supports'          => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'       => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'query_var'         => true,
        'taxonomies'          => array( 'category', 'post_tag' ),
    );

    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type('event', $args);
}

add_shortcode("eventshortcode", "events_section_generator");
function events_section_generator($attr){
	$html_code = '';
	$events = new WP_Query(array(
		'post_type' => 'event',
        'cat' => 10,
		'posts_per_page' => (empty($attr['qty']) ==  true ? -1 : $attr['qty']),
		'sort' => 'post_date',
	));
	if( $events->have_posts() ) {
		while($events->have_posts()) {
			$events->the_post();
			$eventslink = get_permalink($events->ID);
			$eventstitle = get_the_title();
			$eventsdate = get_the_date('F d, Y');
            $event_img = get_the_post_thumbnail_url($events->ID, 'thumbnail');
            if(empty($event_img)) {
                $event_img = "/wp-content/uploads/2023/05/0fd27f22-149b-3c48-8933-9e808bb72981.png";
            }
            $event_content = wp_strip_all_tags( get_the_content());
            $event_description =  substr($event_content, 0, 100);

			$html_code .=
			    '<div class="events-content" onclick="'.$eventslink.'">
                    <div class="event-img">
                        <a href="'.$eventslink.'"><img src="' . $event_img . '" alt=""/></a>
                    </div>
                    <div class="event-details">
                        <h4 class="events-title"><a href="'.$eventslink.'">'. $eventstitle .'</a></h4>
                        <span class="events-date"><i class="fa-regular fa-clock"></i> Ran on '. $eventsdate .'</span>
                        <p class="events-text">'. $event_description .'</p>
                        <a href="'.$eventslink.'">Read More</a>
                    </div>
                    
                </div>';
		}
	}
	// Restore original Post Data
	wp_reset_postdata();
	return
	'<section class="events-section">
        <div class="events-block">
            <div class="owl-carousel owl-theme past-event-slider">
                '. $html_code .'
            </div>
        </div>
    </section>';
}

add_shortcode("eventshortcode-dummy", "events_section_generator2");
function events_section_generator2($attr){
	$html_code = '';
	$events = new WP_Query(array(
		'post_type' => 'event',
        'cat' => 10,
		'posts_per_page' => (empty($attr['qty']) ==  true ? -1 : $attr['qty']),
		'sort' => 'post_date',
	));
	if( $events->have_posts() ) {
		while($events->have_posts()) {
			$events->the_post();
			$eventslink = get_permalink($events->ID);
			$eventstitle = get_the_title();
			$eventsdate = get_the_date('F d, Y');
            $event_img = get_the_post_thumbnail_url($events->ID, 'thumbnail');
            if(empty($event_img)) {
                $event_img = "/wp-content/uploads/2023/05/0fd27f22-149b-3c48-8933-9e808bb72981.png";
            }
            $event_content = wp_strip_all_tags( get_the_content());
            $event_description =  substr($event_content, 0, 100);

			$html_code .=
			    '<div class="events-content" onclick="'.$eventslink.'">
                    <div class="event-img">
                        <a href="'.$eventslink.'"><img src="' . $event_img . '" alt=""/></a>
                    </div>
                    <div class="event-details">
                        <h4 class="events-title"><a href="'.$eventslink.'">'. $eventstitle .'</a></h4>
                        <span class="events-date"><i class="fa-regular fa-clock"></i> Ran on '. $eventsdate .'</span>
                        <p class="events-text">'. $event_description .'</p>
                        <a href="'.$eventslink.'">Read More</a>
                    </div>
                    
                </div>';
		}
	}
	// Restore original Post Data
	wp_reset_postdata();
	return
	'<section class="events-section">
        <div class="events-block">
            <div class="owl-carousel owl-theme">
                '. $html_code .'
            </div>
        </div>
    </section>';
}


add_shortcode("current_eventshortcode", "current_events_section_generator");
function current_events_section_generator($attr){
	$html_code = '';
	$current_events = new WP_Query(array(
		'post_type' => 'event',
        'cat' => 9,
		'posts_per_page' => (empty($attr['qty']) ==  true ? -1 : $attr['qty']),
		'sort' => 'post_date',
        'showposts' => 3,
	));
	if( $current_events->have_posts() ) {
		while($current_events->have_posts()) {
			$current_events->the_post();
			$current_eventslink = get_permalink($current_events->ID);
			$current_eventstitle = get_the_title();
			$current_eventsdate = get_the_date('F d, Y');
            $current_event_img = get_the_post_thumbnail_url($current_events->ID, 'thumbnail');
            if(empty($current_event_img)) {
                $current_event_img = "/wp-content/uploads/2023/05/0fd27f22-149b-3c48-8933-9e808bb72981.png";
            }
            $current_event_content = wp_strip_all_tags( get_the_content());
            $current_event_description =  substr($current_event_content, 0, 50);

            $date_string = get_field('event_date');
            $time_string = get_field('event_time');
            $city_string = get_field('event_city');


			$html_code .=
			    '<div class="current-events-content" onclick="'.$current_eventslink.'">
                    <div class="current-event-img">
                        <a href="'.$current_eventslink.'"><img src="' . $current_event_img . '" alt=""/></a>
                    </div>
                    <div class="current-event-details">
                        <h4 class="current-events-title"><a href="'.$current_eventslink.'">'. $current_eventstitle .'</a></h4>
                        <span class="events-date mb-5 d-block"><i class="fa-regular fa-calendar"></i> <b>Date & Time</b>: '. $date_string .', '. $time_string .'</span>
                        <span class="events-date"><i class="far fa-map"></i> <b>Location</b>: '. $city_string .'</span>
                        <p class="current-events-text">'. $current_event_description .'</p>
                        <a href="'.$current_eventslink.'">Read More</a>
                    </div>                    
                </div>';
		}
	}
	// Restore original Post Data
	wp_reset_postdata();
	return
	'<section class="current-events-section">
        <div class="current-events-block">
            <div class="current-event-list">
                '. $html_code .'
            </div>
        </div>
    </section>';
}


add_action( 'init', 'custom_post_custom_feedback' );
// The custom function to register a Feedback post type
function custom_post_custom_feedback() {
    // Set the labels. This variable is used in the $args array
    $labels = array(
        'name'               => __( 'Feedbacks' ),
        'singular_name'      => __( 'Feedback' ),
        'add_new'            => __( 'Add Feedback' ),
        'add_new_item'       => __( 'Add Feedback' ),
        'edit_item'          => __( 'Edit Feedback' ),
        'new_item'           => __( 'New Feedback' ),
        'all_items'          => __( 'All Feedbacks' ),
        'view_item'          => __( 'View Feedback' ),
        'search_items'       => __( 'Search Feedback' ),
        'featured_image'     => 'Profile Image',
        'set_featured_image' => 'Add Profile Image'
    );
// The arguments for our post type, to be entered as parameter 2 of register_post_type()
    $args = array(
        'labels'            => $labels,
        'menu_icon'         => 'dashicons-editor-quote',
        'description'       => 'Holds our Feedback post specific data',
        'public'            => true,
        'menu_position'     => 5,
        'supports'          => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'       => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'query_var'         => true,
    );
    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type('feedback', $args);
}


add_shortcode("feednackshortcode", "feedback_section_generator");
function feedback_section_generator($attr){
	$html_code = '';
	$feedback = new WP_Query(array(
		'post_type' => 'feedback',
		'posts_per_page' => (empty($attr['qty']) ==  true ? -1 : $attr['qty']),
		'sort' => 'post_date'
	));
	if( $feedback->have_posts() ) {
		while($feedback->have_posts()) {
			$feedback->the_post();
			$feedbacktitle = get_the_title();
			// $feedback_member_desc = get_field('feedback_member_descrption',$feedback->get_the_ID());
			// $social_group = get_field('feedback_member_social_links',$feedback->get_the_ID());
			$feedback_profile_img = wp_get_attachment_url(get_post_thumbnail_id($feedback->get_the_ID()));
			// if(empty($feedback_profile_img)) {
			// 	$feedback_profile_img = "/wp-content/uploads/2021/08/feedback-member-img.png";
			// }
			
			$html_code .=
			'<div class="feedback-slide">
                <img src="' . $feedback_profile_img . '" >
                <h4 class="feedback-title">'. $feedbacktitle .'</h4>
			</div>';
		}
	}
	// Restore original Post Data
	wp_reset_postdata();
	return
	'<section class="feedback-section"><div class="owl-carousel owl-theme">
        '. $html_code .'
    </section>';
}

