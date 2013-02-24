<?php get_header(); the_post(); ?>

<div id="page-title" class="post-title">
	<div class="container">
		<div class="post-info">
			<div class="date">
				<em></em>
				<a href="<?php the_permalink() ?>"><?php print get_the_date() ?></a>
			</div>
			<div class="comments">
				<em></em>
				<a href="#comments"><?php comments_number( __('No Comments', 'snapshot'), __('One Comment', 'snapshot'), __('% Comments', 'snapshot') ); ?></a>
			</div>
			
			<?php $category = get_the_category(); if(!empty($category)) : ?>
				<div class="category">
					<em></em>
					<?php the_category(', '); ?>
				</div>
			<?php endif ?>
		</div>
		
		<h1>
			<?php the_title() ?>
		</h1>
		
		<div class="nav">
			<?php previous_post_link('%link') ?>
			<?php next_post_link('%link') ?>
		</div>
	</div>
</div>

<?php get_template_part('viewer') ?>
	
<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
	<div class="container">
		<div id="post-share">
			<?php if(so_setting('social_display_share')) get_template_part('share') ?>
		</div>
		
		<div id="post-main">
			<div class="entry-content">
				
				
				
				<div id="main_frame" style="width:490px; margin:auto; position:relative; font-size: 9pt; color: #777777;">
				<canvas id="cv" style="border: 1px solid;" width="490" height="368"></canvas>
				<script type="text/javascript">
					var canvas = document.getElementById('cv');				//get the canvas to draw on
					var viewer = new JSC3D.Viewer(canvas);					//initialize a new 3D viewer object
					var logoTimerID = 0;
					viewer.setParameter('SceneUrl', "<?php echo wp_get_attachment_url(get_custom_field('stl:raw')); ?>");			//set the URL of 3D model file
					viewer.setParameter('RenderMode', 'smooth');				//set render mode to "smooth", which should always be the case

					viewer.init();								//always need init()
					viewer.update();							//always do update() to put it on the screen
					
				</script>
				
				<?php the_content() ?>
				
				<?php global $numpages; if(!empty($numpages) || get_the_tag_list() != '') : ?>
					<div class="clear"></div>
				<?php endif; ?>
				
				<?php wp_link_pages() ?>
				<?php the_tags() ?>
			</div>
			<div class="clear"></div>
			
			<div id="single-comments-wrapper">
				<?php comments_template() ?>
			</div>
		</div>
		
		<div id="post-images">
			<?php
				$children = get_children(array(
					'post_mime_type' => 'image',
					'post_parent' => get_the_ID(),
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'post_mime_type' => 'image', 
					'order' => 'ASC',
					'orderby' => 'menu_order ID'
				));
			
				foreach($children as $child){
					$exclude = get_post_meta($child->ID, 'sidebar_exclude', true);
					if(!empty($exclude)) continue;
					
					$src = wp_get_attachment_image_src($child->ID, 'single-large');
					?>
					<div class="image">
						<?php print '<a href="'.get_attachment_link($child->ID).'" data-width="'.$src[1].'" data-height="'.$src[2].'">' ?>
						<?php print wp_get_attachment_image($child->ID, 'post-thumbnail', false, array('class' => 'thumbnail')); ?>
						<?php print '</a>' ?>
					</div>
					<?php
					
				}
			?>
		</div>

	</div>
	<div class="clear"></div>
</div>

<?php get_footer() ?>
