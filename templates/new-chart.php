<div class="wrap" id="<?php echo "{$this->plugin}-dashboard"; ?>">
	<div class="icon32" id="<?php echo "{$this->plugin}-icon"; ?>"></div><br> <!-- Add the icon to the page -->
	<h2><?php echo "{$this->title}-New Chart"; ?></h2>

	<!-- Ajax loading jiff -->
	<div class="admin-ajax-loading">
		<h2> Please wait while we process your request.  This may take a while</h2>
		<img src="<?php echo "{$this->url}assets/img/ajax-scroll-bar.gif" ?>" alt="">
	</div>

	<!-- Display admin messages -->
	<div class="<?php echo "admin-messages"; ?>"></div>

	<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
	<?php settings_errors("{$this->prefix}_dashboard");?>

	
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
					
			<!-- meta boxes container 1 -->
			<div id="postbox-container-1" class="postbox-container">


				<!-- Settings fields form -->
				<form id ="chart-form" action='' method='post'>

					<?php settings_fields("{$this->prefix}_dashboard");?>

					<!-- Used to save closed meta boxes and their order */ -->
			   	<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
			   	<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

					
					<!-- Display reset settings button -->
					<div class="reset-settings">
						<?php submit_button('Reset Settings', 'delete', 'reset_settings', false, array('onclick' => "return confirm ('Are you sure?');"));?>
					</div>
					
					<?php do_meta_boxes($this->dashboard_hook, 'side', null);?>
					<?php submit_button("Save Chart", 'primary', 'save-chart');?>
					
				</form>

				</div><!-- #post-box-container-1 -->

				<!-- meta boxes container 2 -->
				<div id="postbox-container-2" class="postbox-container">

					<div class="chart-meta-box">
						<?php do_meta_boxes($this->dashboard_hook, 'normal', null);?>
					</div>
				</div> <!-- #post-box-container-2 -->

			</div> <!-- #post-body -->
			<br class="clear">
		</div> <!-- #poststuff -->


</div> <!-- .wrap -->