<?php
class Custom_Featured_Post_Widget extends WP_Widget {
	function Custom_Featured_Post_Widget() {
		$widget_ops = array( 'description' => __( 'Display Featured posts') );
		parent::WP_Widget(false, __('Featured Posts Groups (FPMCG)'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$show      				= empty($instance['show']) ? '6' : $instance['show'];
		$displayposttitle    	= !isset($instance['displayposttitle']) ? 1 : $instance['displayposttitle'];
		$displaypostimage    	= !isset($instance['displaypostimage']) ? 1 : $instance['displaypostimage'];
		$displaydesc    		= !isset($instance['displaydesc']) ? 1 : $instance['displaydesc'];
		$displaydate    		= !isset($instance['displaydate']) ? 1 : $instance['displaydate'];
		$displayauthor  		= !isset($instance['displayauthor']) ? 1 : $instance['displayauthor'];
		$title        			= empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		
		$before_post_content 	= apply_filters( 'widget_text', empty( $instance['before_post_content'] ) ? '<div>' : $instance['before_post_content'], $instance );
		$after_post_content 	= apply_filters( 'widget_text', empty( $instance['after_post_content'] ) ? '</div>' : $instance['after_post_content'], $instance );
		
		$before_title 			= apply_filters( 'widget_text', empty( $instance['before_title'] ) ? '<h2>' : $instance['before_title'], $instance );
		$after_title 			= apply_filters( 'widget_text', empty( $instance['after_title'] ) ? '</h2>' : $instance['after_title'], $instance );
						
		
		$before_post_body 		= apply_filters( 'widget_text', empty( $instance['before_post_body'] ) ? '<div>' : $instance['before_post_body'], $instance );
		$after_post_body 		= apply_filters( 'widget_text', empty( $instance['after_post_body'] ) ? '<div style="clear:both;"></div></div>' : $instance['after_post_body'], $instance );
		
		$before_image 			= apply_filters( 'widget_text', empty( $instance['before_image'] ) ? '<div>' : $instance['before_image'], $instance );
		$after_image 			= apply_filters( 'widget_text', empty( $instance['after_image'] ) ? '</div>' : $instance['after_image'], $instance );
		
		$before_display_data 	= apply_filters( 'widget_text', empty( $instance['before_display_data'] ) ? '<div>' : $instance['before_display_data'], $instance );
		$after_display_data 	= apply_filters( 'widget_text', empty( $instance['after_display_data'] ) ? '</div>' : $instance['after_display_data'], $instance );
		
		$before_post_title 		= apply_filters( 'widget_text', empty( $instance['before_post_title'] ) ? '<div>' : $instance['before_post_title'], $instance );
		$after_post_title 		= apply_filters( 'widget_text', empty( $instance['after_post_title'] ) ? '</div>' : $instance['after_post_title'], $instance );
		
		$before_date 			= apply_filters( 'widget_text', empty( $instance['before_date'] ) ? '<div>' : $instance['before_date'], $instance );
		$after_date 			= apply_filters( 'widget_text', empty( $instance['after_date'] ) ? '</div>' : $instance['after_date'], $instance );
		
		$before_description 	= apply_filters( 'widget_text', empty( $instance['before_description'] ) ? '<div>' : $instance['before_description'], $instance );
		$after_description 		= apply_filters( 'widget_text', empty( $instance['after_description'] ) ? '</div>' : $instance['after_description'], $instance );

		$before_author_name 	= apply_filters( 'widget_text', empty( $instance['before_author_name'] ) ? '<div>' : $instance['before_author_name'], $instance );
		$after_author_name 		= apply_filters( 'widget_text', empty( $instance['after_author_name'] ) ? '</div>' : $instance['after_author_name'], $instance );
		
		$css_style 				=  apply_filters( 'widget_text', empty( $instance['css_style'] ) ? '' : $instance['css_style'], $instance );
		
		global $wpdb;
		if (count($instance['post_cat_type']) > 0) {
			foreach($instance['post_cat_type'] as $pcattype) {
				global $wpdb;
				$query = "
					SELECT *
					FROM {$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta m1
					  ON ( {$wpdb->prefix}posts.ID = m1.post_id )
					WHERE
					{$wpdb->prefix}posts.post_type = 'post'
					AND {$wpdb->prefix}posts.post_status = 'publish'
					AND ( m1.meta_key = 'post_cat_type-for-page' AND find_in_set( ".$pcattype.", m1.meta_value ) <>0 )
					GROUP BY {$wpdb->prefix}posts.ID
					ORDER BY {$wpdb->prefix}posts.post_date DESC
					LIMIT 0,{$show};";	
				
				$posts_list = $wpdb->get_results($query);
						
				if ($css_style != '') { ?>
					<style type="text/css">
						<?php echo $css_style;?>
					</style>
				<?php
				}
				//echo $before_widget;
				$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
				
				if (count($posts_list) > 0) { 
					echo $before_post_content;

					if ( $title ) {
						if ($before_title && $after_title) {
							echo $before_title.$title.$after_title;
							
							
														echo $before_title.$title.$after_title;

						} else {
							echo "<h2>".$title.$after_title."</h2>";
						}
					} else {
						if ($before_title && $after_title) {
							echo $before_title."Recent Post".$after_title;
						} else {
							echo "<h2>Recent Post</h2>";
						}
					}
					foreach ($posts_list as $posts) {
						$images = get_children( array( 'post_parent' => $posts->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'ID', 'order' => 'ASC', 'numberposts' => 999 ) );
						$authordata = get_userdata($posts->post_author); 
						echo $before_post_body;
						$featured_image_id = get_post_meta( $posts->ID, '_thumbnail_id', true );

						if ( $featured_image_id != ""  && $displaypostimage == '1' ) :

                            $image_img_tag = wp_get_attachment_image( $featured_image_id, array(65,65) );

                            echo $before_image;
                        ?>
                            <a title="<?php echo $posts->post_title;?>" href="<?php echo get_permalink( $posts->ID );?>"><?php echo $image_img_tag; ?></a>   

                        <?php
                            echo $after_image;
                        else:
							if ( $images && $displaypostimage == '1' ) :
								$total_images = count( $images );
								$image = array_shift( $images );
								$image_img_tag = wp_get_attachment_image( $image->ID, array(65,65) );
								echo $before_image; ?>
								<a title="<?php echo $posts->post_title;?>" href="<?php echo get_permalink( $posts->ID ); ?>">
									<?php echo $image_img_tag; ?>
								</a>	
								<?php echo $after_image;
							endif; 
						endif;
						echo $before_display_data;
						if ( $displayposttitle == '1' ) :
							echo $before_post_title ? $before_post_title : "<h3>";?>
								<a title="<?php echo $posts->post_title;?>" rel="bookmark" href="<?php echo get_permalink( $posts->ID ); ?>"><?php echo $posts->post_title;?></a>
							<?php echo $after_post_title ? $after_post_title : "</h3>";
						endif;
						if  ($displaydate == '1') : 
							echo $before_date;
							echo date("F j, Y", strtotime($posts->post_date));
							echo $after_date;
						endif; 
						if  ($displaydesc == '1') : 
							$post_desc = $posts->post_content;
							$post_desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $post_desc, ENT_QUOTES, get_option('blog_charset') ) ) ) );
							$post_desc = wp_html_excerpt( $post_desc, 360 );
							if ( '[...]' == substr( $post_desc, -5 ) ) {
								$post_desc = substr( $post_desc, 0, -5 ) . '[&hellip;]';
							} elseif ( '[&hellip;]' != substr( $post_desc, -10 ) ) {
								$post_desc .= ' [&hellip;]';
							}
							$post_desc = esc_html( $post_desc );
							echo $before_description;
							echo $post_desc;
							echo $after_description;
						endif; 
						if  ($displayauthor == '1') : 
							echo $before_author_name;?>
							By <?php echo $authordata->user_nicename;?>
							<?php echo $after_author_name;
						endif; 
						echo $after_display_data;
						echo $after_post_body; 
					}
					echo $after_post_content;
				}
			}
	  	}
	}
	
	function update( $new_instance, $old_instance ) {  
		$instance = array();
		$instance['post_cat_type']  	= absint($new_instance['post_cat_type']);
		$instance['show']           	= absint($new_instance['show']);
		$instance['displayposttitle']   = $new_instance['displayposttitle'];
		$instance['displaypostimage']   = $new_instance['displaypostimage'];
		$instance['displaydesc']    	= $new_instance['displaydesc'];
		$instance['displaydate']    	= $new_instance['displaydate'];
		$instance['displayauthor']  	= $new_instance['displayauthor'];
		$instance['title']          	= strip_tags( $new_instance['title'] );
                
        if ( current_user_can('unfiltered_html') ) {
		
			$instance['before_post_content']    = empty( $new_instance['before_post_content'] ) ? '<div>' : $new_instance['before_post_content'];
			$instance['after_post_content']     = empty( $new_instance['after_post_content'] ) ? '</div>' : $new_instance['after_post_content'];
			
			$instance['before_title']           = empty( $new_instance['before_title'] ) ? '<h2>' : $new_instance['before_title'];
			$instance['after_title']            = empty( $new_instance['after_title'] ) ? '</h2>' : $new_instance['after_title'];
			
			$instance['before_post_body']       = empty( $new_instance['before_post_body'] ) ? '<div>' : $new_instance['before_post_body'];
			$instance['after_post_body']        = empty( $new_instance['after_post_body'] ) ? '<div style="clear:both;"></div></div>' : $new_instance['after_post_body'];
			
			$instance['before_image']           = empty( $new_instance['before_image'] ) ? '<div>' : $new_instance['before_image'];
			$instance['after_image']            = empty( $new_instance['after_image'] ) ? '</div>' : $new_instance['after_image'];
			
			$instance['before_display_data']    = empty( $new_instance['before_display_data'] ) ? '<div>' : $new_instance['before_display_data'];
			$instance['after_display_data']     = empty( $new_instance['after_display_data'] ) ? '</div>' : $new_instance['after_display_data'];
			
			$instance['before_post_title']      = empty( $new_instance['before_post_title'] ) ? '<div>' : $new_instance['before_post_title'];
			$instance['after_post_title']       = empty( $new_instance['after_post_title'] ) ? '</div>' : $new_instance['after_post_title'];
			
			$instance['before_date']            = empty( $new_instance['before_date'] ) ? '<div>' : $new_instance['before_date'];
			$instance['after_date']             = empty( $new_instance['after_date'] ) ? '</div>' : $new_instance['after_date'];
			
			$instance['before_description']     = empty( $new_instance['before_description'] ) ? '<div>' : $new_instance['before_description'];
			$instance['after_description']      = empty( $new_instance['after_description'] ) ? '</div>' : $new_instance['after_description'];
			
			$instance['before_author_name']     = empty( $new_instance['before_author_name'] ) ? '<div>' : $new_instance['before_author_name'];
			$instance['after_author_name']      = empty( $new_instance['after_author_name'] ) ? '</div>' : $new_instance['after_author_name'];
			
			$instance['css_style']              = $new_instance['css_style'];
                    
		} else {
			$instance['before_post_content']    =empty( $new_instance['before_post_content'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_post_content']) ) );
			$instance['after_post_content']     = empty( $new_instance['after_post_content'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_post_content']) ) );
			
			$instance['before_title']           = empty( $new_instance['before_title'] ) ? '<h2>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_title']) ) );
			$instance['after_title']            = empty( $new_instance['before_title'] ) ? '</h2>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_title']) ) );
			
			$instance['before_post_body']       = empty( $new_instance['before_post_body'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_post_body']) ) );
			$instance['after_post_body']        = empty( $new_instance['after_post_body'] ) ? '<div style="clear:both;"></div></div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_post_body']) ) );
			
			$instance['before_image']           = empty( $new_instance['before_image'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_image']) ) );
			$instance['after_image']            = empty( $new_instance['after_image'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_image']) ) );
			
			$instance['before_display_data']    = empty( $new_instance['before_display_data'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_display_data']) ) );
			$instance['after_display_data']     = empty( $new_instance['after_display_data'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_display_data']) ) );
			
			$instance['before_post_title']      = empty( $new_instance['before_post_title'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_post_title']) ) );
			$instance['after_post_title']       = empty( $new_instance['after_post_title'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_post_title']) ) );
			
			$instance['before_date']            = empty( $new_instance['before_date'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_date']) ) );
			$instance['after_date']             = empty( $new_instance['after_date'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_date']) ) );
			
			$instance['before_description']     = empty( $new_instance['before_description'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_description']) ) );
			$instance['after_description']      = empty( $new_instance['after_description'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_description']) ) );
			
			$instance['before_author_name']     = empty( $new_instance['before_author_name'] ) ? '<div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['before_author_name']) ) );
			$instance['after_author_name']      = empty( $new_instance['after_author_name'] ) ? '</div>' : stripslashes( wp_filter_post_kses( addslashes($new_instance['after_author_name']) ) );
			
			$instance['css_style']              = stripslashes( wp_filter_post_kses( addslashes($new_instance['css_style']) ) );
		}
        $instance['post_cat_type'] = $new_instance['post_cat_type'];
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array( 'show' => '6', 'displayposttitle' => '1', 'displaypostimage' => '1', 'displaydesc' => '1', 'displaydate' => 1, 'displayauthor' => 1, 'title' => '', 'before_desc' => '', 'before_post_content' => '<div>', 'after_post_content' => '</div>', 'before_title' => '<h2>', 'after_title' => '</h2>', 'before_post_content' => '<div>', 'after_post_content' => '</div>', 'before_post_body' => '<div>', 'after_post_body' => '<div style="clear:both;"></div></div>','before_image' => '<div>', 'after_image' => '</div>', 'before_display_data' => '<div>', 'after_display_data' => '</div>','before_post_title' => '<div>', 'after_post_title' => '</div>', 'before_date' => '<div>', 'after_date' => '</div>','before_description' => '<div>', 'after_description' => '</div>', 'before_author_name' => '<div>', 'after_author_name' => '</div>' ); 
		$opts = mytheme_get_post_category_type( true );
		$instance = wp_parse_args( (array) $instance, $defaults );
					   
		$post_cat_type = (int) $instance['post_cat_type'];
		$show = (int) $instance['show']; 
		$displayposttitle = (int) $instance['displayposttitle'];
		$displaypostimage = (int) $instance['displaypostimage'];
		$displaydesc = (int) $instance['displaydesc'];
		$displaydate = (int) $instance['displaydate'];
		$displayauthor = (int) $instance['displayauthor'];
		$title   = strip_tags($instance['title']);
                               
		$before_post_content 		= esc_textarea($instance['before_post_content']);
		$after_post_content 		= esc_textarea($instance['after_post_content']);
		
		$before_title 				= esc_textarea($instance['before_title']);
		$after_title 				= esc_textarea($instance['after_title']);
		
		$before_post_body 			= esc_textarea($instance['before_post_body']);
		$after_post_body 			= esc_textarea($instance['after_post_body']);
		
		$before_image 				= esc_textarea($instance['before_image']);
		$after_image 				= esc_textarea($instance['after_image']);
		
		$before_display_data 		= esc_textarea($instance['before_display_data']);
		$after_display_data 		= esc_textarea($instance['after_display_data']);
		
		$before_post_title 			= esc_textarea($instance['before_post_title']);
		$after_post_title 			= esc_textarea($instance['after_post_title']);
		
		$before_date 				= esc_textarea($instance['before_date']);
		$after_date 				= esc_textarea($instance['after_date']);
		
		$before_description 		= esc_textarea($instance['before_description']);
		$after_description 			= esc_textarea($instance['after_description']);
		
		$before_author_name 		= esc_textarea($instance['before_author_name']);
		$after_author_name 			= esc_textarea($instance['after_author_name']);
		
		$css_style 					= esc_textarea($instance['css_style']);
                
		if (isset($_POST)) {
			$post_type_ids = $_POST['widget-' . $this->id_base][$this->number]['post_cat_type'];
			if (count($post_type_ids) > 0) {
				$instance['post_cat_type'] = $post_type_ids;
			}
		}
		echo '<p>'. esc_html__('Widget title:') . '
                        <input  id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></p>';
                
		echo '<p>' . esc_html__('Featured posts groups to display:').'<br />';
		if (count($opts) > 0) {
			foreach ($opts as $key => $val) {
			   echo '<input type="checkbox" name="' . $this->get_field_name('post_cat_type') . '[]" value="'.$key.'"'; 
			   if (is_array($instance['post_cat_type']) && count($instance['post_cat_type']) > 0) {
					echo (in_array($key, $instance['post_cat_type'])? 'checked="checked"': '');
			   }
			   echo ' />'.$val.'<br />';
			}
		}
		echo '</p>';
        echo '<p>' . esc_html__('Number of posts to display:') . ' <select id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '">';
		for ( $i = 1; $i <= 10; ++$i ) 
			echo "<option value='$i' " . ( esc_attr($show) == $i ? "selected='selected'" : '' ) . ">$i</option>";
		echo '	</select> </p>';
		echo '<p>' . esc_html__('Display Post Title: ') . '<input type="radio" id="'.$this->get_field_id('displayposttitle').'1" name="' . $this->get_field_name('displayposttitle') . '" value="1"'. ( esc_attr($displayposttitle) == '1' ? "checked='checked'" : '' ) .'>Yes</input>';
		echo '<input type="radio" id="'.$this->get_field_id('displayposttitle').'2" name="' . $this->get_field_name('displayposttitle') . '" value="0"'. ( esc_attr($displayposttitle) == '0' ? "checked='checked'" : '' ) .'>No</input>';
		echo '<p>' . esc_html__('Display Post Image: ') . '<input type="radio" id="'.$this->get_field_id('displaypostimage').'1" name="' . $this->get_field_name('displaypostimage') . '" value="1"'. ( esc_attr($displaypostimage) == '1' ? "checked='checked'" : '' ) .'>Yes</input>';
		echo '<input type="radio" id="'.$this->get_field_id('displaypostimage').'2" name="' . $this->get_field_name('displaypostimage') . '" value="0"'. ( esc_attr($displaypostimage) == '0' ? "checked='checked'" : '' ) .'>No</input>';				
		echo '<p>' . esc_html__('Display Description: ') . '<input type="radio" id="'.$this->get_field_id('displaydesc').'1" name="' . $this->get_field_name('displaydesc') . '" value="1"'. ( esc_attr($displaydesc) == '1' ? "checked='checked'" : '' ) .'>Yes</input>';
		echo '<input type="radio" id="'.$this->get_field_id('displaydesc').'2" name="' . $this->get_field_name('displaydesc') . '" value="0"'. ( esc_attr($displaydesc) == '0' ? "checked='checked'" : '' ) .'>No</input>';
		echo '<p>' . esc_html__('Display Date: ') . '<input type="radio" id="'.$this->get_field_id('displaydate').'1" name="' . $this->get_field_name('displaydate') . '" value="1"'. ( esc_attr($displaydate) == '1' ? "checked='checked'" : '' ) .'>Yes</input>';
		echo '<input type="radio" id="'.$this->get_field_id('displaydate').'2" name="' . $this->get_field_name('displaydate') . '" value="0"'. ( esc_attr($displaydate) == '0' ? "checked='checked'" : '' ) .'>No</input>';
		echo '<p>' . esc_html__('Display Author: ') . '<input type="radio" id="'.$this->get_field_id('displayauthor').'1" name="' . $this->get_field_name('displayauthor') . '" value="1"'. ( esc_attr($displayauthor) == '1' ? "checked='checked'" : '' ) .'>Yes</input>';
		echo '<input type="radio" id="'.$this->get_field_id('displayauthor').'2" name="' . $this->get_field_name('displayauthor') . '" value="0"'. ( esc_attr($displayauthor) == '0' ? "checked='checked'" : '' ) .'>No</input></p>';
		
		echo '<p> <label for="'. $this->get_field_id('before_post_content') .'">'. _e( 'Before Widget Block:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_post_content').'" name="'. $this->get_field_name('before_post_content').'" type="text" value="'. $before_post_content .'" /></label>
                      </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_post_content') .'">'. _e( 'After Widget Block:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_post_content').'" name="'. $this->get_field_name('after_post_content').'" type="text" value="'. $after_post_content .'" /></label>
			  </p>';
		
		echo '<p>
	<label for="'. $this->get_field_id('before_title') .'">'. _e( 'Before Widget Title:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_title').'" name="'. $this->get_field_name('before_title').'" type="text" value="'. $before_title .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_title') .'">'. _e( 'After Widget Title:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_title').'" name="'. $this->get_field_name('after_title').'" type="text" value="'. $after_title .'" /></label>
			  </p>';
		
		echo '<p>
	<label for="'. $this->get_field_id('before_post_body') .'">'. _e( 'Before Post Body:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_post_body').'" name="'. $this->get_field_name('before_post_body').'" type="text" value="'. $before_post_body .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_post_body') .'">'. _e( 'After Post Body:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_post_body').'" name="'. $this->get_field_name('after_post_body').'" type="text" value="'. $after_post_body .'" /></label>
			  </p>';
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_iamge') .'">'. _e( 'Before Image:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_image').'" name="'. $this->get_field_name('before_image').'" type="text" value="'. $before_image .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_image') .'">'. _e( 'After Image:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_image').'" name="'. $this->get_field_name('after_image').'" type="text" value="'. $after_image .'" /></label>
			  </p>';
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_display_data') .'">'. _e( 'Before Post Data:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_display_data').'" name="'. $this->get_field_name('before_display_data').'" type="text" value="'. $before_display_data .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_display_data') .'">'. _e( 'After Post Data:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_display_data').'" name="'. $this->get_field_name('after_display_data').'" type="text" value="'. $after_display_data .'" /></label>
			  </p>';
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_post_title') .'">'. _e( 'Before Post Title:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_post_title').'" name="'. $this->get_field_name('before_post_title').'" type="text" value="'. $before_post_title .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_post_title') .'">'. _e( 'After Post Title:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_post_title').'" name="'. $this->get_field_name('after_post_title').'" type="text" value="'. $after_post_title .'" /></label>
			  </p>';	
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_date') .'">'. _e( 'Before Date:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_date').'" name="'. $this->get_field_name('before_date').'" type="text" value="'. $before_date .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_date') .'">'. _e( 'After Date:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_date').'" name="'. $this->get_field_name('after_date').'" type="text" value="'. $after_date .'" /></label>
			  </p>'; 
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_description') .'">'. _e( 'Before Description:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_description').'" name="'. $this->get_field_name('before_description').'" type="text" value="'. $before_description .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_description') .'">'. _e( 'After Description:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_description').'" name="'. $this->get_field_name('after_description').'" type="text" value="'. $after_description .'" /></label>
			  </p>'; 
			  
		echo '<p>
	<label for="'. $this->get_field_id('before_author_name') .'">'. _e( 'Before Author Name:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('before_author_name').'" name="'. $this->get_field_name('before_author_name').'" type="text" value="'. $before_author_name .'" /></label>
			  </p>';
		echo '<p>
	<label for="'. $this->get_field_id('after_author_name') .'">'. _e( 'After Author Name:', FB_RSSI_TEXTDOMAIN ) .' <input class="widefat" id="'. $this->get_field_id('after_author_name').'" name="'. $this->get_field_name('after_author_name').'" type="text" value="'. $after_author_name .'" /></label>
			  </p>';
		
        echo '<p>
			<label for="'. $this->get_field_id('css_style') .'">'. _e( 'CSS for widget:', FB_RSSI_TEXTDOMAIN ) .' <textarea class="widefat" rows="16" cols="20" id="'. $this->get_field_id('css_style').'" name="'. $this->get_field_name('css_style').'">'.$css_style.'</textarea></label>                       
                      </p>';
	}
}

add_action( 'widgets_init', 'customPostWidget_init' );

function customPostWidget_init() {
	return register_widget('Custom_Featured_Post_Widget');
}

