<!-- Create a header in the default WordPress 'wrap' container -->
<?php 
  $layout = $payload["chart"]["layout"];
  $__layout = "{$this->prefix}__layout";
  // print_r($payload)

?>

<div class="<?php echo "{$this->prefix}__admin"; ?> wrap">

 <?php //var_dump($payload) ?>
 
  <!-- Show module title -->
    <h2 class="page-title">
      <?php echo esc_html(get_admin_page_title()) ?>
      <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Go Back' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
    </h2>
    
  <!-- Admin messages -->
  <div class="admin-messages"></div>

  <!-- Content wrapper -->
  <div class="edit-chart content-wrapper">

    <div class="chart-view">

      <!-- Loading spinner -->
      <!-- <img class="spinner" src="<?php //echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner"> -->
      <div class="loading hidden">Loading...</div> 

      <!-- Warning heading -->
      <div class="warning">To view a chart, you must select/upload a file, select a sheet, and chart type</div>

      <div class="plotly">
        <!-- PLot goes here -->
        <div class="chart" id="<?php echo "{$this->prefix}__plotlyChart" ?>"></div>
        <div class="min-max-avg-table">
          <?php require "{$this->path}templates/min-max-inputs.php"; ?>
          <div class="table hidden" id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable"; ?>" ></div>
        </div>
      </div>

    </div>

    <div class="chart-options">

      <!-- Create the form that will be used to render our options -->
      <form id="<?php echo "{$this->prefix}__chartOptionsForm" ?>" name="<?php echo "{$this->prefix}__chartOptionsForm" ?>" novalidate action="javascript:void(0);">

        <div class="accordion accordion__level-1 main__Acc">
          
          <div class="ac fileUploadPanel">
            <h2 class="ac-header"><div class="ac-trigger">File Upload</div></h2>
            <div class="ac-panel hidden">
              <?php require "{$this->path}templates/file-upload.php"; ?>
            </div>
          </div>

          <div class="ac basicOptionsPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/basic-options.php"; ?></div>
          </div>

          <div class="ac titlePanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Title</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac legendPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Legend</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac hoverlabelPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Hoverlabel</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac modebarPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Modebar</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac xaxisPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Bottom Axis</div></h2>
            <?php $chart_axis = "xaxis" ?>
            <div class="ac-panel"><?php require "{$this->path}templates/chart-axis.php"; ?></div>
          </div>

          <div class="ac topAxisPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Top Axis</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac leftAxisPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Left Axis</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac rightAxisPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Right Axis</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac tracesPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Traces</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-2 traces__Acc"></div>
            </div>
          </div>

        </div>

      </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

    </div>  <!-- .chart-options -->

   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->