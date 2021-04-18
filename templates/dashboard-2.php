<?php $selected_chart_type = ""; ?>


<div class="wrap" id="<?php echo "{$this->plugin}-dashboard"; ?>">
	<div class="icon32" id="<?php echo "{$this->plugin}-icon"; ?>"></div><br> <!-- Add the icon to the page -->
	<h2><?php echo "{$this->title}-Dashboard"; ?></h2>
	<form method = "post" action = "" >
		<a href ="javascript:;" class ="add-new-h2" id ="add-new-chart">Add New Chart</a>
		<?php //submit_button( "Add New Chart", "primary", "add-new-chart", true, array() ); ?>
	</form>


	<!-- Display admin messages -->
	<div class="<?php echo "admin-messages"; ?>"></div>

	<div id ="new-chart">

		<form id ="file-upload" enctype="multipart/form-data" action="" method="post">
			<input type='hidden' id = 'created-date' name='created-date' value='<?php echo strftime("%Y-%m-%d %H:%M:%S",time()); ?>'>
			<input type='hidden' id = 'updated-date' name='updated-date' value='<?php echo strftime("%Y-%m-%d %H:%M:%S",time()); ?>'>
			<?php wp_nonce_field("{$this->prefix}_{$this->file_upload_nonce}", $this->file_upload_nonce); ?>

			<!-- File input field -->
			<div class="col mt-4">
				<input type='file' class = "clickable file-upload btn btn-success" name='file-upload[]' id='file-upload'>  
			</div>

			<div><?php submit_button("Upload File", 'primary', "file-upload-submit", false);?></div>

		</form>

	</div>



		<div class="new-chart">
				<?php do_meta_boxes($this->dashboard_hook, 'advanced', null);?>
			</div>


	<?php settings_errors("{$this->prefix}_dashboard");?>
	<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->



	<!-- Ajax loading jiff -->
	<div class="admin-ajax-loading">
		<h2> Please wait while we process your request.  This may take a while</h2>
		<img src="<?php echo "{$this->url}assets/img/ajax-scroll-bar.gif" ?>" alt="">
	</div>



	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<form action='options.php' method='post'>
				<?php settings_fields("{$this->prefix}_dashboard");?>
				<div id="postbox-container-1" class="postbox-container">
					
	<!-- Display reset settings button -->
	<div class="reset-settings">
		<form action='options.php' method='post'>
			<?php settings_fields($this->prefix . "_dashboard");?>
			<input type="hidden" name="active_tab" value="<?php echo $this->prefix . "_dashboard" ?>">
			<?php submit_button('Reset Settings', 'delete', 'reset_settings', false, array('onclick' => "return confirm ('Are you sure?');"));?>
		</form>
	</div>
	<br>
					<?php do_meta_boxes($this->dashboard_hook, 'side', null);?>
					<?php submit_button("Save", 'primary', 'submit');?>
				</div>
			</form>
			<div class="chart-meta-boxes">
				<?php do_meta_boxes($this->dashboard_hook, 'normal', null);?>
			</div>
		</div> <!-- d#post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->
</div> <!-- .wrap -->