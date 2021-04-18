<div id = "" class="<?php echo $this->plugin; ?> <?php echo $this->class_id ?> widecolumn">

	<?php if ( $atts['show-title'] ) : ?>
		<h2><?php _e( $this->name, $this->plugin  ); ?></h2>
	<?php endif; ?>

			<!-- Display success message -->
	<div class = "user-notice" >
		<?php echo $this->display_user_messages(); ?>
	</div>


	<?php if ( $atts['form-success']) : ?>
		<div><a href="<?php echo home_url($atts['continue-page']) ?>">Click here to continue</a></div>
	<?php else :

	// display registration fields
	if ($this->class_id == "user_registration") {
		$fields = (get_option($this->class_module()."_registration_fields"))? get_option($this->class_module()."_registration_fields") : array();
		usort($fields, function($a, $b) {
			return strnatcmp($a['field_position'], $b['field_position']);
		});
	} elseif ($this->class_id == "user_login") {
		$fields = $this->mandatory_login_fields();
	} elseif ($this->class_id == "user_lost_pwd") {
		$fields = $this->mandatory_lost_pwd_fields();
	} elseif ($this->class_id == "user_reset_pwd") {
		$fields = $this->mandatory_reset_pwd_fields();
	} elseif ($this->class_id == "security") {
		$fields = $this->mandatory_security_fields();
	}
	?>

	<form action="<?php echo $atts['form-action'];  ?>" method="post">
		
		<?php wp_nonce_field("{$this->prefix}_{$this->class_id}", $this->class_id);


		foreach ($fields as $id => $field ) :

			// Retreive session value if any
			if (isset($field['field_value']) && $field['field_value']) {
				$value = $field['field_value'];
			} else {
				$value =  isset( $atts[$this->class_id][$field['field_id']]) ? esc_attr($atts[$this->class_id][$field['field_id']]) : null ;
			}

			?>
			<p class="form-row">
				<label for="<?php echo $field['field_id'] ?>"><?php _e( $field['field_label'], $this->plugin) ?><?php echo (isset($field['field_required']) && $field['field_required'])? "<strong>*</strong>" : "" ?></label>
				<?php
				echo $this->render_field_type($field, $value);
				?>
			</p>
			<?php

		endforeach;

		if ($this->class_id === "user_registration"):
			?>
			<p class="password-hint">
				<?php echo wp_get_password_hint(); ?> <br /><br />
				* Required field
			</p>

			<?php if ( !empty(get_option($this->class_module()."_captcha")['recaptcha_enable']) && !empty(get_option($this->class_module()."_captcha")['recaptcha_site_key'] )) : ?>
				<div class="recaptcha"><div class="g-recaptcha" data-sitekey="<?php echo get_option($this->class_module()."_captcha")['recaptcha_site_key']; ?>"></div></div>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ($this->class_id === "user_login"): ?>
			<input type="hidden" name="redirect_to" value="<?php echo $atts['redirect_to'] ?>" />
		<?php endif; ?>



		<p><input type="submit" id = "form-submit" name="form-submit" value="<?php _e( $atts['submit-title'], $this->plugin  ); ?>" /></p>


			<span class="clearfix"></span>

		</form>


		<?php if ($this->class_id === "user_login"): ?>


			<div class = "links">

				<div class="user-forgotpassword">Lost your password? <a href="<?php echo wp_lostpassword_url(); ?>"> <?php _e( ' Click here to reset', $this->plugin ); ?></a></div>
				<div class="user-register">New User?<a href="<?php echo wp_registration_url(); ?>"><?php _e( ' Click here to register', $this->plugin ); ?></a></div>

			</div>
		<?php endif; ?>

	<?php endif; ?>





	<?php if ($this->class_id === "user_lost_pwd" && ! $atts['form-success']): ?>
		<p class="lost-pwd-message"><?php _e( "{$this->tab_settings($this->class_module()."_dialogs")['lostpassword_message']}", $this->plugin ); ?></p>
	<?php endif; ?>


</div>

