<?php if(have_posts()) : ?>
	<div id="post-loop">
		<div class="container">
			<?php while(have_posts()): the_post(); ?>
				<div <?php post_class('post') ?>>
				
				
				
				<?php 
		/*		global $wpdb;
				$query =  "select * from $wpdb->postmeta";
				$result= $wpdb->get_results($query);
	print_r($result);
		*/		?>
				
				
					<div class="post-background">


						<?php if(has_post_thumbnail()) : the_post_thumbnail('post-thumbnail', array('class' => 'thumbnail')) ?>
						<?php else : ?><img src="<?php print get_template_directory_uri() ?>/images/defaults/no-thumbnail.jpg" width="310" height="420" class="thumbnail" />
						<?php endif 												?>
						<?php
						global $wpdb;
						$post_id = get_the_ID();
						$query = "
						select post_name from {$wpdb->prefix}posts 
						where post_type='custom_post'
						and ID=(select meta_value from {$wpdb->prefix}postmeta where meta_key = 'post_cat_type-for-page' and post_id = '$post_id')";
				  
						
						
						
						$result=$wpdb->get_results($query);
					//	print_r($result);
					//	echo $result;
						
						
						
						?>
						<?php if($result != NULL){?>
						<div class="banner">
							<span class="corner-banner"> 
            					<em><?php print_r($result[0]->post_name);   ?></em> 
       						</span> 
						</div>

<?php }?>

						<div class="post-content">
						

						
							<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
							<div class="excerpt">
							
							
							
								<?php the_excerpt() ?>
							</div>
							
							<div class="date">
								<em></em>
								<a href="<?php the_permalink() ?>"><?php print get_the_date() ?></a>
							</div>
							
							<?php $comments = get_comment_count(get_the_ID()); ?>
							<?php if(!empty($comments['approved'])) : ?>
								<div class="comments">
									<em></em>
									<a href="<?php the_permalink() ?>#comments"><?php printf(__('%s Comments', 'snapshot'), $comments['approved']) ?></a>
								</div>
							<?php endif; ?>
							
						</div>

						<div class="corner corner-se"></div>
					</div>
				</div>
			<?php endwhile; ?>
			<div class="clear"></div>
			
			<div id="page-navigation">
				<?php if(function_exists('wp_pagenavi')) : wp_pagenavi(); ?>
				<?php else : posts_nav_link(' ', __('Previous Page', 'snapshot'), __('Next Page', 'snapshot')); print '<div class="clear"></div>'; endif;?>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="page">
		<div class="container">
			<div id="post-main">
				<div class="entry-content">
					<p><?php print so_setting('messages_no_results') ?></p>
				</div>
			</div>
	
		</div>
		<div class="clear"></div>
	</div>
<?php endif; ?>
