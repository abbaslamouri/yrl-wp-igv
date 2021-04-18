<div id = "" class="<?php echo $this->plugin; ?> <?php echo $this->class_id ?> widecolumn">

	<?php if ( $atts['show-title'] ) : ?>
		<h2><?php _e( $this->name, $this->plugin  ); ?></h2>
	<?php endif; ?>

	<?php if ($this->class_id === "lost-pwd" && ! $atts['form-success']): ?>
		<p class="lost-pwd-message"><?php _e( "{$class->class_dialogs()['lostpassword_message']}", $this->plugin ); ?></p>
	<?php endif; ?>

			<!-- Display success message -->
		<div class = "user-notice" >
			<?php echo $this->display_user_messages(); ?>
		</div> 

	<?php if ( $atts['form-success']) : ?>

		<div><a href="<?php echo home_url($atts['continue-page']) ?>">Click here to continue</a></div>

	<?php else : ?>


		<form action="<?php echo $atts['form-action'];  ?>" method="post">

			<?php wp_nonce_field('_'.$this->prefix.'_'.$this->class_id, $this->class_id); ?>


			<?php foreach ($class->form_fields() as $id => $field ) :?>

				<?php 

				// if (isset($field['value'])) {
				// 	$value = $field['value'];
				// } elseif ( ! empty($_SESSION[$this->prefix.'_'.$this->class_id]) && isset( $_SESSION[$this->prefix.'_'.$this->class_id][$id]) && $_SESSION[$this->prefix.'_'.$this->class_id][$id]) {
				// 	$value = esc_attr($_SESSION[$this->prefix.'_'.$this->class_id][$id]);
				// } else {
				// 	$value = null;
				// } 

				?>

				<p class="form-row">
					<label for="<?php echo $id; ?>"><?php _e( $field['field_label'], $this->plugin  ); ?><?php echo ($field['field_required'])? "<strong>*</strong>" : "" ?></label>
					<input type="<?php echo $field['field_type'] ?>" name="<?php echo $id ?>" id="<?php echo $id ?>" value = "<?php echo $field['value'] //$value;  ?>" >
				</p>

			<?php endforeach; ?>

			<!-- Unset session -->
			<?php if ( isset($_SESSION[$this->prefix.'_'.$this->class_id])) unset($_SESSION[$this->prefix.'_'.$this->class_id]); ?>

			

			<?php if ($this->class_id === "user_registration"): ?>

				<p class="password-hint">
					<!-- <?php //echo wp_get_password_hint(); ?> <br /><br /> -->
					* Required field
				</p>

				<?php 
				if ( !empty(get_option($class->class_module()."_captcha")['recaptcha_enable']) && !empty(get_option($class->class_module()."_captcha")['recaptcha_site_key'] )) : ?>
					<div class="recaptcha">
						<div class="g-recaptcha" data-sitekey="<?php echo get_option($class->class_module()."_captcha")['recaptcha_site_key']; ?>"></div>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ($this->class_id === "user_login"): ?>
				<input type="hidden" name="redirect_to" value="<?php echo $atts['redirect_to'] ?>" />
			<?php endif; ?>



			<p><input type="submit" id = "form-submit" name="form-submit" value="<?php _e( $atts['submit-title'], $this->plugin  ); ?>"/></p>


			<span class="clearfix"></span>

		</form>


		<?php if ($this->class_id === "user_login"): ?>


			<div class = "links">

				<div class="user-forgotpassword">Lost your password? <a href="<?php echo wp_lostpassword_url(); ?>"> <?php _e( ' Click here to reset', $this->plugin ); ?></a></div>
				<div class="user-register">New User?<a href="<?php echo wp_registration_url(); ?>"><?php _e( ' Click here to register', $this->plugin ); ?></a></div>

			</div>
		<?php endif; ?>

	<?php endif; ?>


</div>

