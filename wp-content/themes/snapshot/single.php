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


<?php /////////////////get rid of the header thumbnail modified?>
<?php //get_template_part('viewer') ?>
	
<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
	<div class="container">
		
		
		<div id="post-main">
			<div class="entry-content">
			
			
				
				<div id="no_use" style="height:20px;"></div>
				
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
				</div>
				
			<div id="post-share">
			<div id="post-meta" >
			<?php 
				$author = get_post_meta(get_the_id(),
				"_simple_fields_fieldGroupID_1_fieldID_3_numInSet_0", true);
				$description = get_post_meta(get_the_id(),
				"_simple_fields_fieldGroupID_2_fieldID_2_numInSet_0", true);
				
				echo "<h3 id='reply-title'>From:<a href='g.cn' style='margin-left:10px'>".$author."</a></h3>";
				
				$thingiverse = simple_fields_value("thingiverse");
				
				if($thingiverse != ""){
					echo "<h3 id='reply-title'>Thingiverse link:</h3>";
					echo "<div class='entry-content' style='margin-top:10px;'>".$thingiverse."</div>";
				}
				
				if($description != ""){
					echo "<h3 id='reply-title'>Description:</h3>";
					echo "<div class='entry-content' style='margin-top:10px;'>".$description."</div>";
				}
			
			
			?>
			</div>
			<?php //if(so_setting('social_display_share')) get_template_part('share') 
			?>
		
		</div>
		
		
		
				<!-- modified here to add download and vote button -->
						<div id="single-vote" class="vote" >
							<?php	
								

							/*************add in vote and download button*/
								if(function_exists('wpv_voting_display_vote')){
									echo "<em><img src = 'wp-content/themes/snapshot/images/sprites/glyphicons_206_ok_2.png' style='width:1em; height:1em;display: inline;margin-top: 9px;position:relative; right: -40px;' /></em>";
									
									wpv_voting_display_vote(get_the_ID());
								}
								
								$is_checked = simple_fields_value('Downloadable');
								if ($is_checked) {
								echo "<div id='down-single' class='down-single'><a href='".wp_get_attachment_url(get_custom_field('stl:raw'))."'><button class='download-itunes' >Download</button></a></div>";} else {
								echo "<div id='down-none' class='down-none' style = 'visibility: hidden;'><a href='".wp_get_attachment_url(get_custom_field('stl:raw'))."'><button class='download-itunes' >Download File</button></a></div>";
								}
								
									
							?>
										<?php if(so_setting('social_display_share')) get_template_part('share') ?>

						</div>
				<?php 

				the_content() ?>
				
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
		

		
		<?php 
		////////////////////////////////////////delete the extra thumbnail
		
		/*
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
*/?>
	</div>
	<div class="clear"></div>
</div>

<?php get_footer() ?>
