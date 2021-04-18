<div class = "<?php echo $this->field['id']; ?>">

	<input 
		class = "" 
		id="<?php echo $this->field['id']; ?>" 
		name="<?php echo $this->field['tab']; ?>[<?php echo $this->field['id']; ?>]" 
		type = "<?php echo $this->field['field_type']; ?>" 
		size = "<?php echo $this->field['field_size']; ?>" 
		placeholder = "<?php echo $this->field['placeholder']; ?>" 
		maxlength="<?php echo $this->field['id_length']; ?>" 
		value="<?php echo esc_attr($this->field['value']); ?>" 
		<?php //echo ($this->field['required'])? 'required' : "" ?> 
		<?php echo ($this->action == 'edit' && $this->field['readonly'])? "readonly" : "";?> 
	/>

	<span class = ""><?php echo $this->field['description']; ?></span>

</div>





