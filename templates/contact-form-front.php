
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
			<h3><?php _e( 'Contact Form', $this->class->plugin  ); ?></h3>
		<?php endif; ?>

		<form id="contact-from" action="<?php //echo site_url( $_SERVER['REQUEST_URI']);?>" method="post">

			<?php

			wp_nonce_field('_'.$this->class->prefix.'_contact_form', 'contact-form');

			$fields = (get_option($this->class_module()."_add_field"))? get_option($this->class_module()."_add_field") : array();

			usort($fields, function($a, $b) {
				//return strcmp($a["position"], $b["position"]);
				return strnatcmp($a['field_position'], $b['field_position']);

			});

			foreach($fields as $field_id => $field) {

				?>
				<p>

				<label for="<?php echo $field_id ?>"><?php _e( $field['field_label'], $this->plugin) ?><?php echo (isset($field['field_required']) && $field['field_required'])? "<strong>*</strong>" : "" ?></label>

				<?php if ($field['field_type'] == 'select') { ?>
					<select id="<?php echo $field_id ?>" name="<?php echo $field_id ?>" >
						<?php 
						$options = explode(", ", $field['field_options']);
						foreach ($options as $key =>  $option) {
							?>
							<option value="<?php echo $key ?>"><?php echo $option ?></option>
						<?php } ?>
					</select>
					<?php 
				} elseif ($field['field_type'] == 'radio') { 
					$options = explode(", ", $field['field_options']);
					foreach ($options as $key =>  $option) {
						?>
						<input type="<?php echo $field['field_type'] ?>" id="<?php echo $field_id ?>" name="<?php echo $field_id ?>" value="<?php echo $key ?>" ><?php echo $option ?>
						<?php 
					}
				} elseif ($field['field_type'] == 'textarea') {
					?>
					<textarea name="<?php echo $field_id ?>" id="<?php echo $field_id ?>" rows="10"><?php echo isset( $atts['contact-form'][$field_id]) ? esc_attr($atts['contact-form'][$field_id]) : null  ?></textarea>
					<?php
				}else {
					?>
					<input type="<?php echo $field['field_type'] ?>" id="<?php echo $field_id ?>" name="<?php echo $field_id ?>" >
					<?php
				}

				?>
						</p>
<?php

			}
		

			?>




			<p class="form-row">
				<label for="cf-name"><?php _e( 'Name', $this->class->plugin  ); ?><strong>*</strong></label>
				<input type="text" name="cf-name" id="cf-name" value = "<?php echo isset( $atts['contact-form']['cf-name']) ? esc_attr($atts['contact-form']['cf-name']) : null  ?>" >
			</p>

			<p class="form-row">
				<label for="cf-subject"><?php _e( 'Subject', $this->class->plugin  ); ?><strong>*</strong></label>
				<input type="text" name="cf-subject" id="cf-subject" value = "<?php echo isset( $atts['contact-form']['cf-subject']) ? esc_attr($atts['contact-form']['cf-subject']) : null  ?>">
			</p>



			<p class="form-row">
				<label for="cf-email"><?php _e( 'Email', $this->class->plugin  ); ?><strong>*</strong></label>
				<input type="email" name="cf-email" id="cf-email" value = "<?php echo isset( $atts['contact-form']['cf-email']) ? esc_attr($atts['contact-form']['cf-email']) : null  ?>" >
			</p>


			<p class="form-row">
				<label for="cf-message"><?php _e( 'Message', $this->class->plugin  ); ?><strong>*</strong></label>
				<textarea name="cf-message" id="cf-message" rows="10"><?php echo isset( $atts['contact-form']['cf-message']) ? esc_attr($atts['contact-form']['cf-message']) : null  ?></textarea>
			</p>

			<p class="contact-form-submit">
				<input type="submit" id = "<?php echo "{$class->prefix}_contact_form_submit" ?>" name="<?php echo "{$class->prefix}_contact_form_submit" ?>" class="contactform-button" value="<?php _e( 'Submit', $this->class->plugin  ); ?>"/>
			</p>

		</form>

	<?php //endif; ?>

</div>