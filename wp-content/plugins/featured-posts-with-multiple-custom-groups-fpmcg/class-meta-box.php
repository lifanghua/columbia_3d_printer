<?php
class RW_Meta_Box_Custom_post {
    protected $_meta_box;
    // create meta box based on given data
    function __construct($meta_box) {
		if (!is_admin()) return;
		$this->_meta_box = $meta_box;
		$this->_meta_box_arr[] = $meta_box;
		$current_page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1, -4);
		add_action('admin_menu', array(&$this, 'add'));
		add_action('save_post', array(&$this, 'save'));
    }
    /// Add meta box for multiple post types
    function add() {
		foreach($this->_meta_box as $meta_box_key => $meta_box) {
			$context = empty($meta_box['context']) ? 'normal' : $meta_box['context'];
			$priority = empty($meta_box['priority']) ? 'high' : $meta_box['priority'];
			foreach ($meta_box['pages'] as $page) {
				add_meta_box($meta_box['id'], $meta_box['title'], array(&$this, 'show'), $page, $context, $priority, array('metakey'=>$meta_box_key));
			}
		}
    }
    function show($post, $metabox) {
        echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
        echo '<table class="form-table">';
		foreach ($this->_meta_box[$metabox['args']['metakey']]['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);
			$desc = isset($field['desc'])?$field['desc']:'';
			$std = isset($field['std'])?$field['std']:'';
			$fid = isset($field['id'])? esc_attr($field['id']): '';

			echo '<p><label for="', $fid, '" style="vertical-align: top;">', wp_filter_post_kses( $field['name'] ), '</label> &nbsp;';
			switch ($field['type']) {
				case 'select':
					$metaarr = explode(',', $meta);
					if ($field['properties'] && count($field['properties']) > 0 && isset($field['properties']['multiple']) && $field['properties']['multiple'] == 'multiple') {
						echo '<select name="',$fid, '[]" id="', $fid, '"';
					} else {
						echo '<select name="',$fid, '" id="', $fid, '"';
					}
					//echo '<select name="',$fid, '" id="', $fid, '"';
					if ($field['properties'] && count($field['properties']) > 0) {
						foreach ($field['properties'] as $propkey => $propoption) {
							echo ' '.$propkey.'="'.$propoption.'"';
						}
					}
					echo  ' >';
					echo '<option value="" disabled="disabled">--Select an option below --</option>';
					foreach ($field['options'] as $key=>$option) {
						$selected = "";
						if (in_array($key, $metaarr)) {
							$selected = "selected='selected'";
						}
						echo '<option value="', esc_attr( $key ), '"', $selected, '>', esc_attr( $option ), '</option>';
					}
					echo '</select>';
					break;
					
				case 'checkbox':
					echo '<input type="checkbox" name="', $fid, '" id="', $fid, '"', checked($meta, 'yes'), ' value="yes" />';
					break;
			}
			
			echo '</p>';
		}
        echo '</table>';
    }
    
    // Save data from meta box
	function save($post_id) {
		if(!isset($_POST['wp_meta_box_nonce'])){
			return $post_id;
		}
		// verify nonce
		if (!wp_verify_nonce($_POST['wp_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
					return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		foreach($this->_meta_box as $meta_box) {
			foreach ($meta_box['fields'] as $field) {
				$name = $field['id'];
				$old = get_post_meta($post_id, $name, true);

				$new = isset($_POST[$field['id']]) ? $_POST[$field['id']] :'' ;

				if ($field['type'] == 'wysiwyg') {
					$new = wp_filter_post_kses( wpautop($new) ) ;
				}else if ($field['type'] == 'textarea') {
					$new = wp_filter_post_kses( $new );
				}else if ($field['type'] == 'select') {
					if (is_array($new) && count($new) > 0) {
						$new = implode(',', $new);
					}
				}else{
					$new = esc_attr( $new ); // may want to have better sanitization here, based on specific field types
		
				}
				if ($new && $new != $old) {
					update_post_meta($post_id, $name, $new);
				} elseif ('' == $new && $old ) {
						delete_post_meta($post_id, $name, $old);
				}
			}
		}
    }
}
?>
