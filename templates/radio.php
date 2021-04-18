
<?php foreach ($payload['fieldOptions'] as $key => $option) : ?> 
  <input
    class = "form-group__radio <?php echo esc_attr($payload['cssClass']); ?>"
    type = "radio"
    id="iwpgv__<?php echo esc_attr($payload['id']); ?>"
    name="iwpgv__<?php echo esc_attr("{$payload['tab']}[{$payload['id']}]"); ?>"
    value="<?php echo esc_attr($key); ?>"
    <?php checked( $payload['value'], $key, true); ?> >
    <span class=""><?php echo esc_html__($option, $this->plugin) ?></span> 
<?php endforeach; ?>



