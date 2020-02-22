<?php

class scripturecloud_widget extends WP_Widget {
	function scripturecloud_widget() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'scripturecloud', 'description' => __("Customizable cloud of your blog's scripture."));

		/* Widget control settings. */
		$control_ops = array('width' => 320, 'height' => 240, 'id_base' => 'scripturecloud-widget');

		/* Create the widget. */
		$this->WP_Widget('scripturecloud-widget', __('A Scripture Cloud'), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		extract($instance);

		echo $before_widget;
		echo $before_title.$title.$after_title;
//print_r($instance);

		echo scripturecloud_show($instance);

		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['min'] = strip_tags($new_instance['min']);
		$instance['max'] = strip_tags($new_instance['max']);
		$instance['smallest'] = strip_tags($new_instance['smallest']);
		$instance['largest'] = strip_tags($new_instance['largest']);

		return $instance;
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = scripturecloud_defaults();
		$instance = wp_parse_args((array) $instance, $defaults);
?>
	<div style="text-align:center">
		<h4>Scripture Cloud Options</h4>
		<span style="line-height:15px"><br /><br /></span>
		<table>
			<tr>
				<td><strong><?php _e('Title') ?></strong></td>
				<td>
					<?php _e('Title shown in sidebar.') ?><br/>
					<input style="text-align:right" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
				</td>
			</tr>
			<tr>
				<td><strong><?php _e('Min') ?></strong></td>
				<td>
					<?php _e('Scriptures with less than this will not be shown') ?><br/>
					<input style="text-align:right" type="text" id="<?php echo $this->get_field_id('min'); ?>" name="<?php echo $this->get_field_name('min'); ?>" value="<?php echo esc_attr($instance['min']); ?>" />
				</td>
			</tr>
			<tr>
				<td><strong><?php _e('Max') ?></strong></td>
				<td>
					<?php _e('Scriptures with more than this will not be shown') ?><br/>
					<input style="text-align:right" type="text" id="<?php echo $this->get_field_id('min'); ?>" name="<?php echo $this->get_field_name('max'); ?>" value="<?php echo esc_attr($instance['max']); ?>" />
				</td>
			</tr>
			<tr>
				<td><strong><?php _e('Smallest') ?></strong></td>
				<td>
					<?php _e('The smallest element') ?><br/>
					<input style="text-align:right" type="text" id="<?php echo $this->get_field_id('smallest'); ?>" name="<?php echo $this->get_field_name('smallest'); ?>" value="<?php echo esc_attr($instance['smallest']); ?>" />
				</td>
			</tr>
			<tr>
				<td><strong><?php _e('Largest') ?></strong></td>
				<td>
					<?php _e('The largest element') ?><br/>
					<input style="text-align:right" type="text" id="<?php echo $this->get_field_id('largest'); ?>" name="<?php echo $this->get_field_name('largest'); ?>" value="<?php echo esc_attr($instance['largest']); ?>" />
				</td>
			</tr>
		</table>
	</div>
<?php
	}
}
?>