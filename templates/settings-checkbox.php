<input
	type = "checkbox"
	class = "form-group__select <?php echo esc_attr($payload['cssClass']); ?>"
	id="iwpgv__<?php echo esc_attr($payload['id']);  ?>" 
	name="<?php echo esc_attr("{$payload['section']}[{$payload['id']}]"); ?>"
	value = '1' 
	<?php checked( 1, $payload['value'], true );?>
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
/>