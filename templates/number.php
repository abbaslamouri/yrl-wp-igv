<input
  type = "number"
  class = "form-group__input <?php echo esc_attr($payload['cssClass']); ?>"
  id="iwpgv__<?php echo esc_attr($payload['id']); ?>" 
  name="iwpgv__<?php echo esc_attr($payload['id']); ?>" 
  placeholder = "<?php echo esc_attr($payload['placeholder']); ?>" 
  value="<?php echo esc_attr($payload['value']); ?>"
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
  min="<?php echo esc_attr($payload['fieldMin']); ?>" 
  max="<?php echo esc_attr($payload['fieldMax']);  ?>"
  step="<?php echo esc_attr($payload['fieldStep']);  ?>"
  <?php echo ($payload['required'])? "required" : ""; ?>
  data-dependent-field = "<?php echo ($payload['dependentField'])? esc_attr($payload['dependentField'])  : "";?>" 
/>




