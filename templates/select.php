<select 
  class = "form-group__select <?php echo esc_attr($payload['cssClass']); ?>"
  id = "iwpgv__<?php echo esc_attr($payload['id']); ?>"
  name = "iwpgv__<?php echo esc_attr($payload['id']); ?>"
  <?php echo isset($payload['dependentField']) ? "data-dependent-field = '".esc_attr($payload['dependentField'])."'" : '' ; ?>"
  <?php echo ($payload['disabled'])? "disabled" : "";?> 
>
  <?php if ($payload['nullOption']) : ?>
    <option value="" <?php selected( $payload['value'], "", true); ?> ><?php echo esc_html__($payload['nullOption'], $this->plugin); ?></option>
<?php endif; ?>
  <?php foreach ($payload['fieldOptions'] as $key => $payload_option)  :?>
    <option value="<?php echo $key; ?>" <?php selected( $payload['value'], $key, true); ?> ><?php echo esc_html__($payload_option, $this->plugin); ?></option>
  <?php endforeach; ?>
</select>


