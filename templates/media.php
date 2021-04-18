<div class="<?php echo $field_id; ?> form-group image-uploader" >
		<input
			<?php echo ($field['css_class'])? "class='{$field['css_class']}'" : ""; ?>  
			type="text" id="<?php echo $field_id; ?>" 
			name="<?php echo $field['tab']; ?>[<?php echo $field_id; ?>]"
			size = "<?php echo $field['field_size']; ?>" 
			value="<?php echo esc_attr($field['value']); ?>" 
		>
</div>
