<?php

class ljpl_Trakt_Actions_Widget extends WP_Widget {
	
	function ljpl_Trakt_Actions_Widget() {
		// settings	
		$widget_ops = array( 
			'classname' => 'ljpl-trakttv', 
			'description' => 'This widget shows user\'s last actions in trakt.tv.' );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'ljpl-trakttv-actions-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'ljpl-trakttv-actions-widget', 'TraktTV Actions Widget', $widget_ops, $control_ops );
	}

	private function zerofill($number, $positions=2) {
		for($i=1;$i<$positions;$i++)	{
			if($number < pow(10,$i))
				$number = '0' . $number;
		}
		return $number;
	}
	
	function widget( $args, $instance ) {
		
		extract( $args );


		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$username = $instance['username'];
		$apikey = $instance['apikey'];
		if($instance['actionsAll'])
			$actions = '/all';
		else
			$actions = '/' . $instance['actionsList'];
			
		if($instance['typesAll'])
			$types = '/all';
		else
			$types = '/' . $instance['typesList'];
			
		$maxActions = $instance['maxActions'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Podłączamy się do API TraktTV */
		if($username && $apikey) {
			/* RATINGS BLOCK 
			DONT UNCOMMENT BEFORE CACHINGja 
			
			$ch = curl_init();
			$url = 'http://api.trakt.tv/activity/user/json/' . $apikey . '/' . $username . $types . '/rating';
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec ($ch);

			curl_close($ch); 

				
			$ratings = Array();
			$ratingsTemp = json_decode($contents, TRUE);	
			foreach($ratingsTemp['activity'] as $rating) {
				$slug = "";
				if(isset($rating['movie'])) {
					$slug = 'movie';
				} elseif(isset($rating['episode'])) {
					$slug = 'episode';
				}
				
				if($slug) {
					$ratings[$rating[$slug]['url']] = $rating['rating'];
				}
			}
			
			// print ("<pre>" . print_r($ratings,1) . "</pre>");	// -- for debug purposes only	
			*/
			
			// #################################################################
			// ##### Get user's actions
			
			// TODO: refactor $out
			$out = get_transient( 'ljpl-trakttv-useractivity' );
			if( $out === false ) {
			
				$url = 'http://api.trakt.tv/activity/user/json/' . $apikey . '/' . $username . $types . $actions;
				$result = wp_remote_get( $url, array( 'timeout' => 20 ) );
				if( is_wp_error( $result) ) {
					// print_r( $result );
					return; 
				}
			
				if( $result['response']['code'] != 200 )
					return;	// TODO: proper error handling
				
				$out = json_decode($result['body'], TRUE);
				
				// -- cache results for 1h
				// TODO: time settable in settings panel
				// TODO: cache multiple streams
				set_transient( 'ljpl-trakttv-useractivity', $out, 3600 ); // cache results for 1h
			}

			
			// simple check to avoid errors
			if(count($out['activity']) < $maxActions)
				$maxActions = count($out['activity']);
			//echo "<pre>";
			//print_r( $out['activity'] );
			//echo "</pre>";						
			for($i=0;$i<$maxActions;$i++) {
				if(isset($out['activity'][$i]['show'])) { // obsługujemy serial				
					if($out['activity'][$i]['action'] == 'seen') 
						$episodeArr = $out['activity'][$i]['episodes'][0];
					else 
						$episodeArr = $out['activity'][$i]['episode'];
						
					$season = $this->zerofill($episodeArr['season']);
					$episode = $this->zerofill($episodeArr['episode']);
					print '<div class="poster"><a target="_blank" href="' . $out['activity'][$i]['show']['url'] . '"><img src="' . $out['activity'][$i]['show']['images']['poster'] . '" title="'. ucfirst($out['activity'][$i]['action']) .' show: ' . $out['activity'][$i]['show']['title'] . ' - S' . $season . 'E' . $episode . '" /></a></div>';
				} elseif(isset($out['activity'][$i]['movie'])) { // obsługujemy film

					print '<div class="poster"><a target="_blank" href="' . $out['activity'][$i]['movie']['url'] . '"><img src="' . $out['activity'][$i]['movie']['images']['poster'] . '" title="'. ucfirst($out['activity'][$i]['action']) .' movie: ' . $out['activity'][$i]['movie']['title'] . ' - Year: ' . $out['activity'][$i]['movie']['year'] . '" /></a></div>';
					//$debug = print_r($out['activity'][$i],1);				
				}
			}
			//print "</ul>";
			print "<div style=\"clear:both;\"></div>";
			print "<p class='footer'><a href='http://trakt.tv/user/" . $username ."' target='_blank'>Visit my profile on Trakt.tv</a></p>";
		}

		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['apikey'] = $new_instance['apikey'];
		$instance['lastupdate'] = $new_instance['lastupdate'];
		$instance['maxActions'] = intval($new_instance['maxActions']);
		if(!$instance['maxActions'])
			$instance['maxActions'] = 10;
		
		$instance['typesAll'] = ( isset( $new_instance['typesAll'] ) ? 1 : 0 );  
		$instance['typesList'] = $new_instance['typesList'];
		
		$instance['actionsAll'] = ( isset( $new_instance['actionsAll'] ) ? 1 : 0 );  
		$instance['actionsList'] = $new_instance['actionsList'];
		
		return $instance;
	}
	
	/**
	  * refreshCache()
	  * Connects to TraktTV API, downloads contents and stores them in local cache 
	  * NOT IMPLEMENTED YET
	  */
	function refreshCache() {
		global $wbdb;	
		$table_name = $wpdb->prefix . "trakttvcache";
		$sql = "truncate table `{$table_name}`";

		$ch = curl_init();
		$url = 'http://api.trakt.tv/activity/user/json/' . $apikey . '/' . $username . '/all/seen';
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec ($ch);
		curl_close($ch); 
		$out = json_decode($contents, TRUE);

		foreach($out['activity'] as $record) {
			// jeżeli obrabiamy show
			if(isset($record['show'])) {
			}
			
		}

	}
	/**
	  * form()
	  * Displays form with options for widget instance
	  * @param mixed $instance Widget instance
	  */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = Array (
			'title' => 'TraktTV Wordpress Widget',
			'username' => '',
			'apikey' => '',
			'maxActions' => 10,
			'typesAll' => 1,
			'typesList' => 'episode,show,movie',
			'actionsAll' => 1,
			'actionsList' => 'watching,scrobble,checkin,seen,collection,rating,watchlist,shout'	
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- TraktTV login -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Login w serwisie trakt.tv', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" style="width:100%;" />
		</p>

		<!-- TraktTV API key -->
		<p>
			<label for="<?php echo $this->get_field_id( 'apikey' ); ?>"><?php _e('TraktTV API key:', 'example'); ?></label> 
			<input id="<?php echo $this->get_field_id( 'apikey' ); ?>" name="<?php echo $this->get_field_name( 'apikey' ); ?>" value="<?php echo $instance['apikey']; ?>" style="width:100%;" />	
		</p>

		<!-- Max Actions shown -->
		<p>
			<label for="<?php echo $this->get_field_id( 'maxActions' ); ?>"><?php _e('Max number of actions shown:', 'example'); ?></label> 
			<input id="<?php echo $this->get_field_id( 'maxActions' ); ?>" name="<?php echo $this->get_field_name( 'maxActions' ); ?>" value="<?php echo $instance['maxActions']; ?>" style="width:100%;" />
		</p>
		
		<!-- Types to show in widget -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['typesAll'], true ); ?> id="<?php echo $this->get_field_id( 'typesAll' ); ?>" name="<?php echo $this->get_field_name( 'typesAll' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'typesAll' ); ?>"><?php _e('Show all types?'); ?></label>
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'typesList' ); ?>"><?php _e('Types to show: (default: episode,show,movie)', 'example'); ?></label> 
			<input id="<?php echo $this->get_field_id( 'typesList' ); ?>" name="<?php echo $this->get_field_name( 'typesList' ); ?>" value="<?php echo $instance['typesList']; ?>" style="width:100%;" />
		</p>
		
		<!-- Actions to show in widget -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['actionsAll'], true ); ?> id="<?php echo $this->get_field_id( 'actionsAll' ); ?>" name="<?php echo $this->get_field_name( 'actionsAll' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'actionsAll' ); ?>"><?php _e('Show all actions?'); ?></label>
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'actionsList' ); ?>"><?php _e('Actions to show: (default: watching,scrobble,checkin,seen,collection,rating,watchlist,shout)', 'example'); ?></label> 
			<input id="<?php echo $this->get_field_id( 'actionsList' ); ?>" name="<?php echo $this->get_field_name( 'actionsList' ); ?>" value="<?php echo $instance['actionsList']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

