	<input
		type = "<?php echo $payload['fieldType']; ?>"
		class = "form-group__file <?php echo esc_attr($payload['cssClass']); ?>"
		<?php echo (isset($payload['cssClass']))? "class='{$payload['cssClass']}'" : ""; ?> 
		id="iwpgv__<?php echo $payload["id"]; ?>"
		name="iwpgv__<?php echo $payload['id']; ?>" 
		<?php echo (isset($payload['multiple']))? "multiple" : "" ; ?> 
	/>
 
  