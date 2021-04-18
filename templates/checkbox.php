
<input
	type = "checkbox"
	class = "form-group__select <?php echo esc_attr($payload['cssClass']); ?>"
	id="iwpgv__<?php echo esc_attr($payload['id']);  ?>" 
	name="iwpgv__<?php echo esc_attr($payload['id']); ?>" 
	<?php checked( 1, $payload['value'], true );?>
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
  <?php echo ($payload['readOnly'])? "readOnly" : "";?> 
  data-dependents = "<?php echo ($payload['dependents'])? implode(',', $payload['dependents'])  : "";?>" 
/>


