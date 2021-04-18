<input
  type="text"
  class = "form-group__input <?php echo esc_attr($payload['cssClass']); ?>"
  id="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  name="iwpgv__<?php echo esc_attr($payload['id']); ?>"
  value = "<?php echo esc_attr($payload['value']); ?>"
/>
<input
  type="button"
  class="button-secondary"
  value="Upload New File"
  id="iwpgv__media-upload-btn"
/>