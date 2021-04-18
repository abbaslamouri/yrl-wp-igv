<input
  type = "color-picker"
  class = "form-group__input <?php echo esc_attr($payload['cssClass']); ?>"
  id="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  name="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  size = "<?php echo esc_attr($payload['fieldSize']); ?>"
  placeholder = "<?php echo esc_attr($payload['placeholder']); ?>"
  pattern = "<?php echo esc_attr($payload['pattern']); ?>"
  value = "<?php echo esc_attr($payload['value']); ?>"
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
  <?php echo isset($payload['required']) ?  esc_attr($payload['required']) : ''; ?>"
/>






