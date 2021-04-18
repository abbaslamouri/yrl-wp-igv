<div class="<?php echo $this->plugin; ?> contact-form">

		<!-- Display success message -->
		<div class = "user-notice" >
			<?php echo $this->display_user_messages(); ?>
		</div> 

		<div class="ajax-loading">
			<h2> Please wait while we process your request</h2>
			<img src="<?php echo AXL_WP_Ultimate_URL."assets/img/ajax-scroll-bar.gif" ?>" alt="">
		</div>


		<?php if ( $atts['show-title'] ) : ?>
			<h3><?php _e( 'Contact Form', $this->plugin  ); ?></h3>
		<?php endif; ?>

		<form id="contact-from"  method="post">

			<?php

			wp_nonce_field("{$this->prefix}_{$this->form_nonce}", $this->form_nonce);

			$fields = (get_option($this->class_module()."_fields"))? get_option($this->class_module()."_fields") : array();

			//usort($fields, function($a, $b) {
				//return strcmp($a["position"], $b["position"]);
				//return strnatcmp($a['field_position'], $b['field_position']);

			//});
			
			foreach($fields as $field_id => $field) {

				// Retreive session value if any
				$value = isset( $atts[$this->class_id][$field_id]) ? esc_attr($atts[$this->class_id][$field_id]) : null ;
				?>
				<p class="form-row">
					<label for="<?php echo $field['field_id'] ?>"><?php _e( $field['field_label'], $this->plugin) ?><?php echo (isset($field['field_required']) && $field['field_required'])? "<strong>*</strong>" : "" ?></label>
					<?php
					echo $this->render_field_type($field, $value);
					?>
				</p>
				<?php

			}
		
			?>
			<p class="contact-form-submit">
				<input type="submit" id = "<?php echo "{$this->prefix}_contact_form_submit" ?>" name="<?php echo "{$this->prefix}_contact_form_submit" ?>" class="contactform-button" value="<?php _e( $atts['submit-title'], $this->plugin  ); ?>"/>
			</p>

		</form>

</div>