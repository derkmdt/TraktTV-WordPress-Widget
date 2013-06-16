<div class="wrap">
	<h2>TraktTV Plugin </h2>
	<p>By <em>Studio Multimedialne ljasinski.pl</em></p>
	<hr />

	<div style="width:300px; float:right; margin-left: -300px;" class="metabox-holder"> 
		<div class="meta-box-sortables">
			<div class='postbox'>
				<h3 class='hndle'>
					<span>Donate</span>
				</h3>
				<div class='inside'>
					
					<p>If you like the plugin and want to keep it's development running, please consider a 
					small donation.</p>
					<div style="margin:10px auto; text-align:center;">
						<form name="paypal-trakttv" action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="3KYX5TTQD5NWU">
						<input type="hidden" name="lc" value="US">
						<input type="hidden" name="item_name" value="TraktTV plugin">
						<input type="hidden" name="item_number" value="plugin settings">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" onclick="document.paypal-trakttv.submit();" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">	
						</form>
					</div>		
				</div>
			</div>
		</div>
	</div>
	
	<div style="margin-right: 320px; width: auto; overflow:hidden;" class="metabox-holder">
		<div class="meta-box-sortables">
			<div class="postbox">
				<h3 class="hndle">
					<span>Settings</span>
				</h3>
				<div class="inside">
					<form method="post" action="options.php"> 
						<?php settings_fields( 'ljpl-trakttv-admin' ); ?>
						<?php //do_settings( 'trakttv-admin' ); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row">Use Plugin's stylesheet</th>
								<td><input name="ljpl-trakttv-use-css" type="checkbox" value="1" <?php checked('1', get_option('ljpl-trakttv-use-css')); ?> /></td>
							</tr>
						</table> 
						<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>		
	
	
	<div style="margin-right: 320px; width: auto; overflow:hidden;" class="metabox-holder">
		<div class="meta-box-sortables">
			<div class="postbox">
				<h3 class="hndle">
					<span>Documentation</span>
				</h3>
				<div class="inside">
					<p>Go to <a href="widgets.php">widgets settings</a> to include the widget into your site. You will need your personal API key. If you don't have one, you can find it on <a target="_blank" href="http://trakt.tv/settings/api">TraktTV's API settings page</a>.</p>
					<p>If you're looking for some kind of documentation or have a question to ask (or bug report), please visit official plugin's website:</p>
						<ul>
							<li><a target="_blank" href="http://www.ljasinski.pl/wordpress-plugins/trakttv-wordpress-widget/?utm_source=wp-trakttv&utm_medium=plugins&utm_campaign=open-source">Polish version</a></li>
							<li><a target="_blank" href="http://www.ljasinski.pl/en/wordpress-plugins/trakttv-wordpress-widget/?utm_source=wp-trakttv&utm_medium=plugins&utm_campaign=open-source">English version</a></li>
						</ul>
				</div>
			</div>
		</div>
	</div>	

</div> <!-- div.wrap -->	
