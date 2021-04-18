<!-- Create a header in the default WordPress 'wrap' container -->
 <div class='<?php echo $this->prefix; ?>'>

 
  <!-- Show module title -->
  <div class="admin-heading">
    <h2 >Chart</h2>
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Go Back' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
  </div>

  <!-- Admin messages -->
  <div class="admin-messages"></div>

  <!-- Content wrapper -->
  <div class="content-wrapper">

    <div class ="edit-chart">

      <div class="chart-view">

        <!-- Loading spinner -->
        <img id = "<?php echo "{$this->prefix}__spinner" ?>" class="loading-spinner hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">

        <!-- Warning heading -->
        <h3 id="<?php echo "{$this->prefix}__warning" ?>" class="warning">
        To view a chart, you must select/upload a file, select a sheet, and chart type
        </h3>

        <!-- PLot goes here -->
        <div id="<?php echo "{$this->prefix}__plotlyChart" ?>"></div>

        <!-- X-axis Min and Max inputs go here -->
        <form id="<?php echo "{$this->prefix}__plotMinMax" ?>" class="form" action="#">

          <div class="form__group hidden">
            <input type="number" id = "<?php echo "{$this->prefix}__rangeMinInput" ?>" class="form__input" placeholder="Range Min" required>
            <label class="form__label" for="<?php echo "{$this->prefix}__rangeMinInput" ?>">Range Min</label>
          </div>

          <div class="form__group hidden">
            <input type="number" id = "<?php echo "{$this->prefix}__rangeMaxInput" ?>" class="form__input" placeholder="Range Max" required>
            <label class="form__label" for="<?php echo "{$this->prefix}__rangeMaxInput" ?>">Range Max</label>
          </div>
          
        
        </form>

        <!-- Data table goes here -->
          <div id="<?php echo "{$this->prefix}__plotlyTable" ?>" class=''></div>
       

        <!-- Min Max Data table goes here -->
          <div id="<?php echo "{$this->prefix}__plotlyMinMaxTable" ?>" class=''></div>
        
      

    

        <!--Dashboard divs-->
        <!-- <div id="<?php // echo "{$this->prefix}__dashboard" ?>">
          <div id="<?php // echo "{$this->prefix}__filter-min-max" ?>" class="<?php // echo "{$this->prefix}__filter-min-max" ?>">
            <div id="<?php // echo "{$this->prefix}__num-range-filter" ?>" class="hidden"></div>
            <div id="<?php // echo "{$this->prefix}__chart-range-filter" ?>" class='hidden'></div>
            <div id="<?php // echo "{$this->prefix}__min-max-table-chart" ?>" class="hidden"></div>
          </div>
        </div> -->

      </div>

      <div class="chart-options">

        <!-- Create the form that will be used to render our options -->
        <form id="<?php echo "{$this->prefix}__chartOptionsForm" ?>" name="<?php echo "{$this->prefix}__chartOptionsForm" ?>" action='options.php' method='post' novalidate>

          <!-- Accordion will be injected here -->

        </form>

      </div>  <!-- .chart-options -->

    </div>
   
  </div> <!-- // END <div class = "content-wrapper"> -->

 

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->