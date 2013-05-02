<?php if(have_posts()) : ?>
	<div id="post-loop">
		<div class="container">
			<div id="content">
			<?php 
			while(have_posts()): the_post(); ?>
				<div <?php post_class('post') ?>>
				
				
					<div class="post-background">
					
					
						<!-- new div added here to display the buttons -->
						<div id="thumb" style="height:234px;">
						<?php if(has_post_thumbnail()) : the_post_thumbnail('post-thumbnail', array('class' => 'thumbnail')) ?>
						<?php else : ?><img src="<?php print get_template_directory_uri() ?>/images/defaults/no-thumbnail.jpg" width="310" height="234" class="thumbnail" />
						<?php endif 												?>
						</div>
						
						<!-- modified here to change the location of the information -->
						
						<div id = "info" class = "info">
						
						<div class= "left">
							<div class = "title_name" style = "font-size: 14px;">
						<h2><a href="<?php the_permalink() ?>"><?php print get_the_title() ?></a></h2>
						</div>
						
						
						<div class = "author_name" style = "margin-top: 1px;">
								<em><img src = "wp-content/themes/snapshot/images/sprites/glyphicons_003_user.png" style="width:1em; height:1em;" /></em>
								<?php $author = simple_fields_value('author_name');  
									$search_url = '?s='.$author;?>
								<a href="<?php echo home_url($search_url); ?>"><?php print simple_fields_value('author_name'); ?></a>
						</div>
							<div class="date" style = "margin-top: 1px;">
								<em><img src = "wp-content/themes/snapshot/images/sprites/glyphicons_054_clock.png" style="width:1em; height:1em;" /></em>
								 <a href="<?php the_permalink() ?>"><?php print get_the_date() ?></a>

							</div>
						
						
						</div>
						<!-- modified here to add download and vote button -->
						<div id="front-vote" class="vote" >
							<?php	
								

							/*************add in vote and download button*/
								if(function_exists('wpv_voting_display_vote')){
									echo "<em><img src = 'wp-content/themes/snapshot/images/sprites/glyphicons_206_ok_2.png' style='width:1em; height:1em;display: inline;margin-top: 9px;position:relative; ' /></em>";
									
									wpv_voting_display_vote(get_the_ID());
								}
								
								$is_checked = simple_fields_value('Downloadable');
								if ($is_checked) {
								echo "<div id='down' class='down'><a href='".wp_get_attachment_url(get_custom_field('stl:raw'))."'><button class='download-itunes' >Download</button></a></div>";} else {
								echo "<div id='down-none' class='down-none' style = 'visibility: hidden;'><a href='".wp_get_attachment_url(get_custom_field('stl:raw'))."'><button class='download-itunes' >Download File</button></a></div>";
								}
								
									
							?>
						</div>
						</div>
						
						
						
						
						<?php
						
						
						////////////////modified here to get the banner and thumbnail
						$post_id = get_the_ID();
						?>
						<?php 
						$category = get_the_category($post_id);
						if($category[0]->cat_name == 'Featured' || $category[0]->cat_name == 'Printed'){ ?>
						<div class="banner">
							<span class="corner-banner"> 
            					<em><?php print_r($category[0]->cat_name);   ?></em> 
       						</span> 
						</div>

<?php }?>

						<div class="post-content" style="cursor: pointer;" onclick="window.location='<?php the_permalink() ?>';">
						
							

						<div class="post-content2">
							
							<div class="excerpt">
							
							
							
								<?php the_excerpt() ?>
							</div>
							
						
						</div>
							
							
							
							
							
							<?php /*$comments = get_comment_count(get_the_ID()); ?>
							<?php if(!empty($comments['approved'])) : ?>
								<div class="comments">
									<em></em>
									<a href="<?php the_permalink() ?>#comments"><?php printf(__('%s Comments', 'snapshot'), $comments['approved']) ?></a>
								</div>
							<?php endif;*/ ?>
							
						</div>
							
						<div class="corner corner-se"></div>
						

					</div>
					
				</div>
			<?php endwhile;?>
			</div>
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
