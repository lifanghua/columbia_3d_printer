<?php

/**
 * Initialize the admin settings
 *
 * @action admin_init
 */
function snapshot_settings_admin_init(){
	// General Stuff
	so_settings_add_section('general', __('General', 'snapshot'));

	so_settings_add_teaser('general', 'search', __('Search in Menu', 'snapshot'), array(
		'description' => __('Display a search link in your menu that slides out a big beautiful search bar.', 'snapshot')
	));
	so_settings_add_field('general', 'latest_posts', 'text', __('Latest Posts Title', 'snapshot'));
	so_settings_add_field('general', 'copyright', 'text', __('Copyright Message', 'snapshot'));
	so_settings_add_teaser('general', 'attribution', __('Attribution Link', 'snapshot'), array(
		'description' => __('Hide or display "Theme By SiteOrigin" link from your footer.', 'snapshot')
	));

	so_settings_add_section('appearance', __('Appearance', 'snapshot'));

	so_settings_add_teaser('appearance', 'style', __('Style', 'snapshot'), array(
		'description' => __('Choose the style of your site.', 'snapshot')
	));

	so_settings_add_field('appearance', 'link', 'color', __('Link Color', 'snapshot'));


	// The slider section
	so_settings_add_section('slider', __('Home Page Slider', 'snapshot'));
	so_settings_add_field('slider', 'enabled', 'checkbox', __('Home Page Slider', 'snapshot'), array());
	so_settings_add_field('slider', 'speed', 'number', __('Transition Delay', 'snapshot'), array(
		'description' => __('Number of milliseconds a photo is displayed for.', 'snapshot')
	));
	so_settings_add_field('slider', 'transition', 'number', __('Transition Delay', 'snapshot'), array(
		'description' => __('How many milliseconds the transition takes.', 'snapshot'),
	));
	so_settings_add_field('slider', 'post_count', 'number', __('Post Count', 'snapshot'), array(
		'description' => __('The number of posts displayed on your home page slider.', 'snapshot'),
	));
	
	so_settings_add_teaser('slider', 'posts', __('Posts', 'snapshot'), array(
		'description' => __('How Snapshot chooses your home page slides.', 'snapshot')
	));

	// Social and sharing
	so_settings_add_section('social', __('Social', 'snapshot'));
	so_settings_add_field('social', 'display_share', 'checkbox', __('Share Buttons', 'snapshot'), array(
		'label' => __('Show share buttons next to posts', 'snapshot')
	));
	so_settings_add_field('social', 'twitter', 'text', __('Twitter Username', 'snapshot'), array('validator' => 'twitter'));
	so_settings_add_field('social', 'recommend', 'checkbox', __('Recommend SiteOrigin', 'snapshot'), array(
		'label' => __('Yes', 'snapshot'),
		'description' => __("Recommends your's and SiteOrigin's Twitter accounts after someone tweets your post.", 'snapshot')
	));

	// Comments
	so_settings_add_section('comments', __('Comments', 'snapshot'));
	so_settings_add_teaser('comments', 'ajax', __('Ajax Comments', 'snapshot'), array(
		'description' => __('Let your visitors post comments without leaving the page.', 'snapshot')
	));

	// Site messages
	so_settings_add_section('messages', __('Site Messages', 'snapshot'));
	so_settings_add_field('messages', '404', 'textarea', __('Error 404 Message', 'snapshot'));
	so_settings_add_field('messages', 'no_results', 'textarea', __('No Search Results', 'snapshot'));
}
add_action('admin_init', 'snapshot_settings_admin_init');

/**
 * Set up the default settings
 * @param $defaults
 * @return array
 *
 * @filter so_theme_default_settings
 */
function snapshot_settings_default($defaults){
	$defaults['general_search'] = true;
	$defaults['general_latest_posts'] = __('Latest Posts', 'snapshot');
	$defaults['general_copyright'] = __('Copyright &copy; {sitename} {year}', 'snapshot');
	$defaults['general_attribution'] = true;

	$defaults['appearance_style'] = 'light';
	$defaults['appearance_link'] = '#dc5c3b';

	$defaults['slider_enabled'] = true;
	$defaults['slider_speed'] = 7500;
	$defaults['slider_transition'] = 500;
	$defaults['slider_post_count'] = 5;
	$defaults['slider_posts'] = 'date';

	$defaults['social_display_share'] = true;
	$defaults['social_recommend'] = false;

	$defaults['comments_ajax'] = true;

	$defaults['messages_404'] = __("We couldn't find what you were looking for.", 'snapshot');
	$defaults['messages_no_results'] = __("No results.", 'snapshot');

	return $defaults;
}
add_filter('so_theme_default_settings', 'snapshot_settings_default');