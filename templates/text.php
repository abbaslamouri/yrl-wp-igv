<?php echo $payload; die;?>

<input
  type = "<?php echo esc_attr($payload['fieldType']); ?>"
  class = "form-group__input <?php echo esc_attr($payload['cssClass']); ?>"
  id="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  name="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  <?php echo isset($payload['value']) ? "value = '".esc_attr($payload['value'])."'" : '' ; ?>"
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
  <?php echo isset($payload['placeholder']) ? "placeholder = '".esc_attr($payload['placeholder'])."'" : '' ; ?>"
  <?php echo isset($payload['pattern']) ? "pattern = '".esc_attr($payload['pattern'])."'" : '' ; ?>"
  <?php echo isset($payload['required']) ? "required = '".esc_attr($payload['required'])."'" : '' ; ?>"
  <?php echo isset($payload['dependentField']) ? "data-dependent-field = '".esc_attr($payload['dependentField'])."'" : '' ; ?>"
/>
<span>?</span>
<div><?php echo $payload["hint"]?></div>






