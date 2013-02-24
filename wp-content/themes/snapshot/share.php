<ul class="networks">
	<li class="network facebook">
		<?php
		$facebook_url = add_query_arg(array(
			'href' => urlencode(get_permalink()),
			'send' => 'false',
			'layout' => 'button_count',
			'layout' => 'button_count',
			'width' => '160',
			'show_faces' => 'false',
			'action' => __('like', 'snapshot'),
			'colorscheme' => 'light',
			'height' => '21',
		), '//www.facebook.com/plugins/like.php');
		?>
		<iframe src="<?php print esc_url($facebook_url) ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:160px; height:21px;" allowTransparency="true"></iframe>
	</li>
	<li class="network twitter">
		<?php
			$related = array();
			if(so_setting('social_twitter')) $related[] = so_setting('social_twitter');
			if(so_setting('social_recommend')) $related[] = 'siteorigin';
			$twitter_url = add_query_arg(array(
				'url' => urlencode(get_permalink()),
				'via' => so_setting('social_twitter'),
				'text' => get_the_title(),
				'related' => implode(',', $related)
			), '//platform.twitter.com/widgets/tweet_button.html');
		?>
		<iframe allowtransparency="true" frameborder="0" scrolling="no" src="<?php print esc_url($twitter_url) ?>" style="width:130px; height:20px;"></iframe>
	</li>
	<li>
		<div class="g-plusone" data-size="medium"></div>
	</li>
</ul>