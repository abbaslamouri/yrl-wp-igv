
<div class = "<?php echo $dashboard->plugin." user-notice"; ?>" >

	<?php var_dump($this); ?>

  <?php foreach ($this->messages as $code => $message) { ?>

    <div class = "<?php echo $this->css_class; ?>" ><?php echo $notice  = ($class->fetch_error_from_code($code) !== "unknown")? $class->fetch_error_from_code($code): $message; ?></div>

  <?php } ?>

</div> 

