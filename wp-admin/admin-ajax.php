<?php
/**
 * WordPress AJAX Process Execution.
 *
 * @package WordPress
 * @subpackage Administration
 *
 * @link http://codex.wordpress.org/AJAX_in_Plugins
 */

/**
 * Executing AJAX process.
 *
 * @since 2.1.0
 */
define( 'DOING_AJAX', true );
define( 'WP_ADMIN', true );

/** Load WordPress Bootstrap */
require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' );

/** Allow for cross-domain requests (from the frontend). */
send_origin_headers();

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die( '345' );

/** Load WordPress Administration APIs */
require_once( ABSPATH . 'wp-admin/includes/admin.php' );

/** Load Ajax Handlers for WordPress Core */
require_once( ABSPATH . 'wp-admin/includes/ajax-actions.php' );

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
nocache_headers();

do_action( 'admin_init' );

/*
	ajax upload handler
*/

function save_attachmentmy() {
	//echo 'begin';
    if ( $_POST['action'] == 'save_attachmentmy' ) {

		$post_title = $_POST['attach_title'];
        $post_parent = $_POST['post_parent'];

		$img = $_POST['png'];

		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		
		//$file = WP_CONTENT_URL . '/uploads/'.$post_parent.'.png';
		$file = '../wp-content/uploads/'.$post_parent.'.png';
		$success = file_put_contents($file, $data);

        if($success == true){
		$filename = WP_CONTENT_URL . '/uploads/'.$post_parent.'.png';
        $attach = array(
            'post_title'    => $post_title,
            'post_parent'   => $post_parent,
            'post_type'     => 'attachment',
            'guid'          => $filename,
            'post_mime_type'=> 'image/png',
            'post_status' => 'inherit'
        );
        
        
        
    	$attachID = wp_insert_attachment( $attach, $filename );
        if( $attachID ) {
            print_r( $attachID );
        }
        }
        else{
        	echo $file;
        }
        //echo $filename;
        //print_r('123');
        //return;
    }
}
add_action( 'wp_ajax_save_attachmentmy', 'save_attachmentmy' );

function set_post_thumbnailmy() {
	if ( $_POST['action'] == 'set_post_thumbnailmy' ) {
		$post = $_POST['post'];
		$thumbnail_id = $_POST['thumbnail_id'];
        $post_parent = $_POST['post_parent'];
		$post = get_post( $post );
		$thumbnail_id = absint( $thumbnail_id );
		if ( $post && $thumbnail_id && get_post( $thumbnail_id ) ) {
			if ( $thumbnail_html = wp_get_attachment_image( $thumbnail_id, 'thumbnail' ) )
				return update_post_meta( $post->ID, '_thumbnail_id', $thumbnail_id );
			else
				return delete_post_meta( $post->ID, '_thumbnail_id' );
		}
	}
	return false;
}
add_action( 'wp_ajax_set_post_thumbnailmy', 'set_post_thumbnailmy' );



$core_actions_get = array(
	'fetch-list', 'ajax-tag-search', 'wp-compression-test', 'imgedit-preview', 'oembed-cache',
	'autocomplete-user', 'dashboard-widgets', 'logged-in',
);

$core_actions_post = array(
	'oembed-cache', 'image-editor', 'delete-comment', 'delete-tag', 'delete-link',
	'delete-meta', 'delete-post', 'trash-post', 'untrash-post', 'delete-page', 'dim-comment',
	'add-link-category', 'add-tag', 'get-tagcloud', 'get-comments', 'replyto-comment',
	'edit-comment', 'add-menu-item', 'add-meta', 'add-user', 'autosave', 'closed-postboxes',
	'hidden-columns', 'update-welcome-panel', 'menu-get-metabox', 'wp-link-ajax',
	'menu-locations-save', 'menu-quick-search', 'meta-box-order', 'get-permalink',
	'sample-permalink', 'inline-save', 'inline-save-tax', 'find_posts', 'widgets-order',
	'save-widget', 'set-post-thumbnail', 'date_format', 'time_format', 'wp-fullscreen-save-post',
	'wp-remove-post-lock', 'dismiss-wp-pointer', 'upload-attachment', 'get-attachment',
	'query-attachments', 'save-attachment', 'save-attachment-compat', 'send-link-to-editor',
	'send-attachment-to-editor', 'save-attachment-order', 'save_attachmentmy','set_post_thumbnailmy' ,
);

// Register core Ajax calls.
if ( ! empty( $_GET['action'] ) && in_array( $_GET['action'], $core_actions_get ) )
	add_action( 'wp_ajax_' . $_GET['action'], 'wp_ajax_' . str_replace( '-', '_', $_GET['action'] ), 1 );

if ( ! empty( $_POST['action'] ) && in_array( $_POST['action'], $core_actions_post ) )
	add_action( 'wp_ajax_' . $_POST['action'], 'wp_ajax_' . str_replace( '-', '_', $_POST['action'] ), 1 );

add_action( 'wp_ajax_nopriv_autosave', 'wp_ajax_nopriv_autosave', 1 );

if ( is_user_logged_in() )
	do_action( 'wp_ajax_' . $_REQUEST['action'] ); // Authenticated actions
else
	do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] ); // Non-admin actions






// Default status
//die( '998' );
