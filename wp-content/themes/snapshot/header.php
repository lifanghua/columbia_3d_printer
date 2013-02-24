<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<link href="rotate.css" rel="stylesheet" />

	<title><?php wp_title('|', true, 'right'); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<script type="text/javascript" src="/js/jsc3d.js"></script>
	<script type="text/javascript" src="/js/jsc3d.console.js"></script>
	<script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class() ?>>

<div id="top-area">
	<div class="container">
		<div id="top-area-wrapper">
			<div id="logo">
				<a href="<?php print esc_url(home_url()) ?>" title="<?php print esc_attr(get_bloginfo('title', 'display') . ' - '.get_bloginfo('description', 'display')) ?>">
					<?php $header = get_custom_header(); if(!empty($header->url)) : ?>
						<img src="<?php print esc_url($header->url) ?>" width="<?php print intval($header->width)?>" height="<?php print intval($header->height)?>" alt="<?php esc_attr(get_bloginfo('title', 'display')) ?>" />
					<?php else : ?>
						<h1><em></em><?php bloginfo('title', 'display') ?></h1>
					<?php endif ?>
				</a>
			</div>
	
			<?php
			wp_nav_menu(array(
				'theme_location' => 'main-menu',
				'container_id' => 'menu-main-menu-container',
				'menu_id' => 'menu-main-menu',
				'depth' => 2,
				'fallback_cb' => 'snapshot_wp_page_menu',
				'walker' => new Snapshot_Walker_Nav_Menu,
			));
			?>
		</div>
	</div>
</div>

<?php if(so_setting('general_search')) get_template_part('premium/searchbar'); ?>