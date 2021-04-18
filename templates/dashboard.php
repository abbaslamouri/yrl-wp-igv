<div class="wrap" id="<?php echo "{$this->plugin}-dashboard"; ?>">

	<div class="icon32" id="<?php echo "{$this->plugin}-icon"; ?>"></div><br> <!-- Add the icon to the page -->
	<h2><?php echo "{$this->title}-Dashboard"; ?></h2>
	<a href ="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "addNewChart"), admin_url('admin.php')); ?>" class ="add-new-h2" id ="addNewChart">Add New Chart</a>
	

	<!-- Ajax loading jiff -->
	<div class="adminAjaxLoading">
		<!-- <h2> Please wait while we process your request.  This may take a while</h2> -->
		<img src="<?php echo "{$this->url}assets/img/ajax-scroll-bar.gif" ?>" alt="">
	</div>

	<!-- Display admin messages -->
	<div class="<?php echo "adminMessages"; ?>"></div>

	<div class="chart-list">
		<?php do_meta_boxes($this->dashboard_hook, 'normal', null);?>
	</div>


</div> <!-- .wrap -->