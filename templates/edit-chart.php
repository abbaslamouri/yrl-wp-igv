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
        <h3 id="<?php echo "{$this->prefix}__warning" ?>" class="warning">To view a chart, you must select/upload a file, select a sheet, and chart type</h3>

        <div class="plotly">
          <!-- PLot goes here -->
          <div class="chart" id="<?php echo "{$this->prefix}__plotlyChart" ?>"></div>
          <div class="<?php echo "{$this->prefix}__min-max-avg-table"; ?>">
            <?php require "{$this->path}templates/min-max-inputs.php"; ?>
            <div class="table" id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable"; ?>" ></div>
          </div>
        </div>

        <!-- Data table goes here -->
        <div id="<?php echo "{$this->prefix}__plotlyTable" ?>" class=''></div>

      </div>

      <div class="chart-options">

        <!-- Create the form that will be used to render our options -->
        <form id="<?php echo "{$this->prefix}__chartOptionsForm" ?>" name="<?php echo "{$this->prefix}__chartOptionsForm" ?>" novalidate action="javascript:void(0);">

          <div class="accordion">
            <?php foreach ( $payload["chart"] as $element ) :?>
              <?php if ($element["panel"]["id"] !== "{$this->prefix}__chartConfigPanel") :?>
                <div class="accordion__toggle hidden <?php echo implode(" ", $element["panel"]["cssClasses"]); ?>">
                  <div class="accordion__heading-title"><?php echo $element["panel"]["title"]?></div>
                  <svg class="accordion__svg">
                    <use href="<?php echo "{$this->url}/assets/img/icons.svg#icon-keyboard_arrow_right" ?>" ></use>
                  </svg>
                </div>
                <div id="<?php echo "{$element["panel"]["id"]}"; ?>" class="accordion__content panel hidden <?php echo implode(" ", $element["panel"]["cssClasses"]); ?>" >
                  <div class="panelIntro">
                    <?php echo $element["panel"]["intro"]?>
                  </div>
                  <div class="accordion"></div>
                </div>
              <?php endif; ?>
            <?php endforeach ?>
          </div>

        </form>

         <!-- Accordion will be injected here -->
         <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

      </div>  <!-- .chart-options -->

    </div>
   
  </div> <!-- // END <div class = "content-wrapper"> -->

 

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->