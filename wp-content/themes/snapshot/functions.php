<?php

define( 'SO_THEME_VERSION' , '1.0.9' );
define( 'SO_THEME_ENDPOINT' , 'http://siteorigin.com' );

include get_template_directory().'/extras/settings/settings.php';
include get_template_directory().'/functions/settings.php';

if(file_exists(get_template_directory().'/premium/functions.php'))
	include get_template_directory().'/premium/functions.php';

if(!defined('SO_IS_PREMIUM')) {
	include get_template_directory().'/extras/premium/premium.php';
	include get_template_directory().'/upgrade/upgrade.php';
}

include get_template_directory().'/extras/update.php';
include get_template_directory().'/extras/admin/admin.php';
include get_template_directory().'/extras/support/support.php';



if(!function_exists('snapshot_setup_theme')) :
/**
 * General theme setup
 * 
 * @action after_setup_theme
 */
function snapshot_setup_theme(){
	// Enable translation
	load_theme_textdomain( 'snapshot', get_template_directory() . '/languages' );
	
	// We're using SiteOrigin theme settings
	so_settings_init();

	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 440;
	
	// The custom header is used for the logo
	add_theme_support('custom-header', array(	
		'flex-width' => true,
		'flex-height' => true,
		'header-text' => false,
	));
	
	// Custom background images are nice
	$background = array();
	switch(so_setting('general_style')){
		case 'light' :
			$background['default-color'] = 'FEFEFE';
			break;
		case 'dark' :
			$background['default-color'] = '333';
			$background['default-image'] = get_template_directory_uri().'/images/dark/bg.png';
			break;
	}
	add_theme_support('custom-background', $background);
	
	add_theme_support('post-thumbnails');
	
	add_theme_support( 'automatic-feed-links' );
	
	set_post_thumbnail_size(310, 420, true);
	add_image_size('single-large', 960, 960, false);
	add_image_size('slider-large', 1600, 1600, false);
	
	// The navigation menus
	register_nav_menu('main-menu', __('Main Menu', 'snapshot'));
	
	add_editor_style();
}
endif;
add_action('after_setup_theme', 'snapshot_setup_theme');


if(!function_exists('snapshot_print_scripts')) :
/**
 * Add the custom style CSS
 * @return mixed
 * 
 * @action wp_print_styles
 */
function snapshot_print_scripts(){
	if(is_admin()) return;

	$header = get_custom_header()
	?>
	<style type="text/css" media="all">
		a{ color: <?php print so_setting('appearance_link') ?>; }
		<?php if($header->url) : ?>
			#menu-main-menu,
			#top-area .menu > ul{
				width: <?php print max(200, 960-$header->width - 20) ?>px;
			}
		<?php endif; ?>
	</style>
	<?php
}
endif;
add_action('wp_print_styles', 'snapshot_print_scripts');


if(!function_exists('snapshot_print_html_shiv')) :
/**
 * Display the HTML 5 shiv conditional
 */
function snapshot_print_html_shiv(){
	?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js" type="text/javascript"></script>
	<![endif]-->
	<!--[if (gte IE 6)&(lte IE 8)]>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/selectivizr.js"></script>
	<![endif]-->
	<?php
}
endif;
add_action('wp_print_scripts', 'snapshot_print_html_shiv');


if(!function_exists('snapshot_setup_widgets')) :
/**
 * Setup the widgets
 * 
 * @action widgets_init
 */
function snapshot_setup_widgets(){
	register_sidebar(array(
		'name' => __('Site Footer', 'snapshot'),
		'id' => 'site-footer',
	));
}
endif;
add_action('widgets_init', 'snapshot_setup_widgets');


if(!function_exists('snapshot_enqueue_scripts')) :
/**
 * Enqueue Snapshot's Scripts.
 * 
 * @action wp_enqueue_scripts
 */
function snapshot_enqueue_scripts(){
	wp_enqueue_style('snapshot', get_stylesheet_uri(), array(), SO_THEME_VERSION);
	
	if(so_setting('appearance_style') != 'light'){
		wp_enqueue_style('snapshot-style', get_stylesheet_directory_uri().'/premium/style-'.so_setting('appearance_style').'.css', array(), SO_THEME_VERSION);
	}

	wp_enqueue_script('imgpreload', get_template_directory_uri() . '/js/jquery.imgpreload.min.js', array('jquery'), '1.4');
	wp_enqueue_script('snapshot', get_template_directory_uri() . '/js/snapshot.min.js', array('jquery', 'imgpreload'), SO_THEME_VERSION);

	wp_localize_script('snapshot', 'snapshot', array(
		'sliderLoaderUrl' => get_template_directory_uri().'/images/slider-loader.gif',
		'imageLoaderUrl' => get_template_directory_uri().'/images/photo-loader.gif',
	));
	
	if(is_home()){
		wp_enqueue_script('imgpreload', get_template_directory_uri() . '/js/jquery.imgpreload.min.js', array('jquery'));
		wp_enqueue_script('snapshot-home', get_template_directory_uri() . '/js/snapshot-home.min.js', array('jquery'), SO_THEME_VERSION);
		wp_localize_script('snapshot-home', 'snapshotHome', array(
			'sliderSpeed' => so_setting('slider_speed'),
			'loaderUrl' => get_template_directory_uri().'/images/slider-loader.gif'
		));
	}
	
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	
	if(is_singular() && so_setting('social_display_share'))
		wp_enqueue_script('snapshot-google-plusone', get_template_directory_uri() . '/js/plusone.min.js', array(), SO_THEME_VERSION);
		
}
endif;
add_action('wp_enqueue_scripts', 'snapshot_enqueue_scripts');


if(!function_exists('snapshot_wp_title')) :
/**
 * Filter the title
 * @param $title
 * @param $sep
 * @param $seplocation
 * @return string
 * 
 * @filter wp_title
 */
function snapshot_wp_title($title, $sep, $seplocation){
	if(trim($sep) != ''){
		if(!empty($title)) {
			$title_array = explode($sep, $title);
		}
		else $title_array = array();
		
		$title_array[] = get_bloginfo('title');
		if(is_home()) $title_array[] = get_bloginfo('description');

		$title_array = array_map('trim', $title_array);
		$title_array = array_filter($title_array);
		
		if($seplocation == 'left') $title_array = array_reverse($title_array);
		
		$title = implode( " $sep ", $title_array );
	}
	
	return $title;
}
endif;
add_filter('wp_title', 'snapshot_wp_title', 10, 3);


if(!function_exists('snapshot_single_comment')) :
/**
 * Display a single comment.
 * 
 * @param $comment
 * @param $depth
 * @param $args
 */
function snapshot_single_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php print get_comment_ID() ?>" <?php comment_class() ?>>
		<?php if(empty($comment->comment_type) || $comment->comment_type == 'comment') : ?>
			<div class="comment-avatar">
				<?php print get_avatar(get_comment_author_email(), 60) ?>
			</div>
		<?php elseif($comment->comment_type == 'trackback' || $comment->comment_type == 'pingback') : ?>
			<div class="pingback-icon"></div>
		<?php endif; ?>
		
		<div class="comment-main">
			<div class="comment-info">
				<span class="author"><?php print get_comment_author_link() ?></span>
				<span class="date"><?php comment_date() ?></span>
		
				<?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])) ?>
			</div>
			<div class="comment-content entry-content">
				<?php comment_text() ?>
			</div>
		</div>
	<?php
}
endif;


if(!function_exists('snapshot_previous_posts_link_attributes')):
/**
 * Add the proper class to the posts nav link
 * @param $attr
 * @return string
 * 
 * @filter previous_posts_link_attributes
 */
function snapshot_previous_posts_link_attributes($attr){
	$attr = 'class="next"';
	return $attr;
}
endif;
add_filter('previous_posts_link_attributes', 'snapshot_previous_posts_link_attributes');


if(!function_exists('snapshot_next_posts_link_attributes')):
/**
 * Add the proper class to the posts nav link
 * @param $attr
 * @return string
 * 
 * @filter next_posts_link_attributes
 */
function snapshot_next_posts_link_attributes($attr){
	$attr = 'class="prev"';
	return $attr;
}
endif;
add_filter('next_posts_link_attributes', 'snapshot_next_posts_link_attributes');


if(!function_exists('snapshot_footer_widget_params')):
/**
 * Set the widths of the footer widgets
 *
 * @param $params
 * @return mixed
 * 
 * @filter dynamic_sidebar_params
 */
function snapshot_footer_widget_params($params){
	// Check that this is the footer
	if($params[0]['id'] != 'site-footer') return $params;

	$sidebars_widgets = wp_get_sidebars_widgets();
	$count = count($sidebars_widgets[$params[0]['id']]);
	$params[0]['before_widget'] = preg_replace('/\>$/', ' style="width:'.round(100/$count,4).'%" >', $params[0]['before_widget']);

	return $params;
}
endif;
add_filter('dynamic_sidebar_params', 'snapshot_footer_widget_params');


if(!function_exists('snapshot_attachment_fields_to_edit')):
/**
 * Add the sidebar exclude field
 * @param $fields
 * @param $post
 * @return array
 * 
 * @filter attachment_fields_to_edit
 */
function snapshot_attachment_fields_to_edit($fields, $post){
	$parent = get_post($post->post_parent);
	if($parent->post_type == 'post'){
		$exclude = get_post_meta($post->ID, 'sidebar_exclude', true);
		$fields['snapshot_exclude'] = array(
			'label' => __('Sidebar Exclude', 'snapshot'),
			'input' => 'html',
			'html' => '<input name="attachments['.$post->ID.'][sidebar_exclude]" id="attachment-'.$post->ID.'-sidebar_exclude" type="checkbox" '.checked(!empty($exclude), true, false).' /> <label for="attachment-'.$post->ID.'-sidebar_exclude">'.__('Exclude', 'snapshot').'</label>',
			'value' => !empty($exclude)
		);
	}
	
	return $fields;
}
endif;
add_filter('attachment_fields_to_edit', 'snapshot_attachment_fields_to_edit', 10, 2);


if(!function_exists('snapshot_attachment_save')):
/**
 * Save the attachment form meta. 
 * @param $post
 * @return mixed
 * 
 * @filter attachment_fields_to_save
 */
function snapshot_attachment_save($post){
	$parent = get_post($post['post_parent']);
	if($parent->post_type == 'post' && !empty($_POST['attachments'][$post['ID']])){
		$current = get_post_meta($post['ID'], 'sidebar_exclude', true);
		$exclude = !empty($_POST['attachments'][$post['ID']]['sidebar_exclude']);
		update_post_meta($post['ID'], 'sidebar_exclude', $exclude, $current);
	}
	
	return $post;
}
endif;
add_filter('attachment_fields_to_save', 'snapshot_attachment_save', 10, 2);

/*
if(!function_exists('snapshot_add_meta_boxes')):
/**
 * Add the relevant metaboxes.
 * 
 * @action add_meta_boxes

function snapshot_add_meta_boxes(){
	if(defined('SO_IS_PREMIUM')) return;
	add_meta_box('snapshot-post-video', __('Post Video', 'snapshot'), 'snapshot_meta_box_video_render', 'post', 'side');
}
endif;
add_action('add_meta_boxes', 'snapshot_add_meta_boxes');
*/
/////////////////////////////modified change the appearance of page in new post


/*-----------------------------------------------------------------------------------*/
/* hide admin_bar on the front end */
/*-----------------------------------------------------------------------------------*/
if(!current_user_can('edit_pages'))
add_filter('show_admin_bar', '__return_false');


/*-----------------------------------------------------------------------------------*/
/* hide some in sidebar */
/*-----------------------------------------------------------------------------------*/
add_action('admin_head', 'remove_sidebar', 0);
function remove_sidebar(){
if(!current_user_can('edit_pages')){
	global $menu;
	//remove_menu_page('index.php');		/* Hides Dashboard menu */
	remove_menu_page('separator1');		/* Hides separator under Dashboard menu*/	
	}
}


/*-----------------------------------------------------------------------------------*/
/* Remove unwanted metabox for some user */
/*-----------------------------------------------------------------------------------*/
function customize_meta_boxes() {
if(!current_user_can('administrator')){
  /* Removes meta boxes from Posts */
  remove_meta_box('postcustom','post','normal');
  remove_meta_box('trackbacksdiv','post','normal');
  remove_meta_box('commentstatusdiv','post','normal');
  remove_meta_box('commentsdiv','post','normal');
  remove_meta_box('tagsdiv-post_tag','post','normal');
  remove_meta_box('postexcerpt','post','normal');
  remove_meta_box('slugdiv','post','normal');
  remove_meta_box('categorydiv','post','normal');
  remove_meta_box('revisionsdiv','post','normal');
  remove_meta_box('snapshot-post-video','post','side');
  remove_meta_box('postimagediv','post','side');
  remove_meta_box('titlediv','post','normal');
}
}
add_action('admin_menu','customize_meta_boxes');


/*-----------------------------------------------------------------------------------*/
/* Remove Warning of Update */
/*-----------------------------------------------------------------------------------*/
 if ( 1 ) {
	 add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
	 add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
	 add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
 }

/*-----------------------------------------------------------------------------------*/
/* Remove Unwanted Admin Menu Items */
/*-----------------------------------------------------------------------------------*/
function remove_admin_menu_items() {
	$remove_menu_items = array( __('Media'), __('Tools'), __('Profile'), __('Comments'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}

add_action('admin_menu', 'remove_admin_menu_items');


/*-----------------------------------------------------------------------------------*/
/* hide admin bar on the backend */
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'fb_remove_admin_bar', 0 );
function fb_remove_admin_bar() {

if(!current_user_can('edit_pages')){
    wp_deregister_script( 'admin-bar' );
    wp_deregister_style( 'admin-bar' );
    remove_action( 'init', '_wp_admin_bar_init' );
    remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
    remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );
    // maybe also: 'wp_head'
    foreach ( array( 'wp_head', 'admin_head' ) as $hook ) {
        add_action(
            $hook,
            create_function(
                    '',
                    "echo '<style>body.admin-bar, body.admin-bar #wpcontent, body.admin-bar #adminmenu {
                         padding-top: 0px !important;
                    }
                    html.wp-toolbar {
                        padding-top: 0px !important;
                    }</style>';"
            )
        );
    }
}
}

/*-----------------------------------------------------------------------------------*/
/* Hide Screen Option and Help for Others */
/* If wanna change the format of the page*/
/* enable the screen option, do it and hide it again*/
/*-----------------------------------------------------------------------------------*/
if(is_admin()):
function remove_screen_options_tab()
{
    return false;
}
add_filter('screen_options_show_screen', 'remove_screen_options_tab');
endif;

if(is_admin()):
function hide_help() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
    </style>';
}
add_action('admin_head', 'hide_help');
endif;


/*-----------------------------------------------------------------------------------*/
/* Change 'post' to 'Models' */
/*-----------------------------------------------------------------------------------*/
function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Models';
    $submenu['edit.php'][5][0] = 'Models';
    $submenu['edit.php'][10][0] = 'Add Models';
//    $submenu['edit.php'][15][0] = 'Status'; // Change name for categories
 //   $submenu['edit.php'][16][0] = 'Labels'; // Change name for tags
    echo '';
}

function change_post_object_label() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Models';
        $labels->singular_name = 'Model';
        $labels->add_new = 'Add Model';
        $labels->add_new_item = 'Add Model';
        $labels->edit_item = 'Edit Models';
        $labels->new_item = 'Model';
        $labels->view_item = 'View Model';
        $labels->search_items = 'Search Models';
        $labels->not_found = 'No Models found';
        $labels->not_found_in_trash = 'No Models found in Trash';
    }
    add_action( 'init', 'change_post_object_label' );
    add_action( 'admin_menu', 'change_post_menu_label' );


/*-----------------------------------------------------------------------------------*/
/* Change Default Text on Title Area */
/*-----------------------------------------------------------------------------------*/
function title_text_input( $title ){
     return $title = 'Enter Your Model Name Here';
}
add_filter( 'enter_title_here', 'title_text_input' );

/*-----------------------------------------------------------------------------------*/
/* Automatically log every user in as guest */
/*-----------------------------------------------------------------------------------*/
function auto_login() {
    if (!is_user_logged_in()) {
        //determine WordPress user account to impersonate
        $user_login = 'guest';

       //get user's ID
        $user = get_userdatabylogin($user_login);
        $user_id = $user->ID;
  
        //login
        wp_set_current_user($user_id, $user_login);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $user_login);
    }
} 
add_action('init', 'auto_login');



/*-----------------------------------------------------------------------------------*/
/* Avoid saving the metabox sequence created by users in the server */
/*-----------------------------------------------------------------------------------*/
add_action('check_ajax_referer', 'prevent_meta_box_order');
function prevent_meta_box_order($action)
{
   if ('meta-box-order' == $action /* && $wp_user == 'santa claus' */) {
      die('-1');
   }
}

/*-----------------------------------------------------------------------------------*/
/* Customize Submit Box */
/*-----------------------------------------------------------------------------------*/
add_action('add_meta_boxes','custom_submit_box');
function custom_submit_box()
{
	
    echo '<style type="text/css">
            .misc-pub-section { display: none !important; }
    </style>';
 //    echo '<script type="text/javascript">
  //          document.getElementById("minor-publishing-actions").style.display = "none";
    //</script>';
}


/*-----------------------------------------------------------------------------------*/
/* TRYING to send email */
/*-----------------------------------------------------------------------------------*/
function email_friends( $post_ID )  
{
	$uni=simple_fields_value('uniuni');
   $friends =$uni."@columbia.edu";
   wp_mail( $friends, "sally's blog updated", 'I just put something on my blog: http://blog.example.com' );

   return $post_ID;
}
add_action( 'publish_post', 'email_friends' );

/*
function send_email_users($post_ID)  {
    $users=simple_fields_value('uniuni');
    mail($users, "New Article is Published", 'A new article have been published on http://www.wordpressapi.com. Please visit');
    return $post_ID;
}
add_action('publish_post', 'send_email_users');

CHANGE THE MENU
HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

function register_custom_menu_page() {
	global $menu;
   add_menu_page('custom menu title', 'custom menu', 'add_users', 'myplugin/myplugin-index.php', '',   plugins_url('myplugin/images/icon.png'), 6);
}
add_action('admin_head', 'register_custom_menu_page');
/////////////////////////////////





if(!function_exists('snapshot_meta_box_video_render')) :
/**
 * Render the video meta box added in snapshot_add_meta_boxes
 */
function snapshot_meta_box_video_render(){
	?><p><?php printf(__('Post videos are available in <a href="%s">Snapshot Premium</a>.', 'snapshot'), admin_url('themes.php?page=premium_upgrade')) ?></p><?php
}
endif;

function snapshot_wp_page_menu($args){
	?><div id="menu-main-menu-container"><?php
	$args['walker'] = new Snapshot_Walker_Page; 
	wp_page_menu($args);
	?></div><?php
}

if(!class_exists('Snapshot_Walker_Page')) :
class Snapshot_Walker_Page extends Walker_Page{
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='sub-menu'><div class='sub-wrapper'><div class='pointer'></div>\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</div></ul>\n";
	}

	function start_el( &$output, $page, $depth, $args, $current_page = 0 ) {
		if ( $depth ) $indent = str_repeat("\t", $depth);
		else $indent = '';

		$output .= $indent . '<li class="menu-item"><a href="' . get_permalink($page->ID) . '">' . apply_filters( 'the_title', $page->post_title, $page->ID ) . '</a>';
	}
}
endif;

if(!class_exists('Snapshot_Walker_Nav_Menu')) :
class Snapshot_Walker_Nav_Menu extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='sub-menu'><div class='sub-wrapper'><div class='pointer'></div>\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</div></ul>\n";
	}
}
endif;


function test(){
	print 'dafds';
}

//add_action("add_attachment", 'test');
//add_action("edit_attachment", 'test');
//add_action("testit", 'test');