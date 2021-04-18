<div class = "<?php echo $field_id; ?>">

  <?php $value = wp_kses(stripslashes_deep($field['value']), wp_kses_allowed_html( 'post' ) ); ?>

  <textarea <?php echo ($field['required'])? "required" : ""; ?>" id="<?php echo $field_id ?>" name="<?php echo $field['tab']."[".$field_id."]" ?>"  <?php echo ($field['textarea_rows'])? "rows='".$field['textarea_rows']."'" : ""; ?>" <?php echo ($field['textarea_cols'])? "cols='".$field['textarea_cols']."'" : ""; ?>" <?php echo ($field['placeholder'])? $field['placeholder'] : ""; ?>" ><?php echo esc_attr($field['value']); ?></textarea>
 

<?php

// // Tiny MCE wp_editor
// $settings = array(
// 	'textarea_name'    => $field['tab']."[".$field_id."]",
//   'teeny' => true,
//   'textarea_rows' =>  $field['textarea_rows'],
//   'tabindex' => 1,
//   'media_buttons'  => false,
// );

// wp_editor($value, $field_id, $settings);

?>

<span class = "description">
  <?php 
  echo "{$field['description']} ";
  echo (isset($field['id_length']))?   "{$field['id_length']} characters max)." : ""; 
  ?>
</span>

</div>

