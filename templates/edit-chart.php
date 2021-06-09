<!-- Create a header in the default WordPress 'wrap' container -->
<?php 
  $layout = $payload["chart"]["layout"];
  $__layout = "{$this->prefix}__layout";

  $config = $payload["chart"]["config"];
  $__config = "{$this->prefix}__config";

  $min_max_avg_table = $payload["chart"]["minMaxAvgTable"];
  $__min_max_avg_table = "{$this->prefix}__minMaxAvgTable";
  // print_r($payload)
  // print_r($layout["xaxis"])


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
          <div class="table " id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable"; ?>" ></div>
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

          <div class="ac configPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Chart Config.</div></h2>
            <div class="ac-panel"><?php //require "{$this->path}templates/chart-config.php"; ?></div>
          </div>

          <div class="ac tracesPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Traces</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-2 traces__Acc"></div>
            </div>
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
            <div class="ac-panel"><?php require "{$this->path}templates/modebar.php"; ?></div>
          </div>

          <div class="ac xaxisPanel hidden">
            <?php 
              $chart_axis = "xaxis";
              $axis_title = "Bottom Axis";
            ?>
            <h2 class="ac-header"><div class="ac-trigger"><?php echo $axis_title; ?></div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/chart-axis.php"; ?></div>
          </div>

          <div class="ac xaxis2Panel hidden">
          <?php
            $chart_axis = "xaxis2";
            $axis_title = "Top Axis";
          ?>
            <h2 class="ac-header"><div class="ac-trigger"><?php echo $axis_title; ?></div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/chart-axis.php"; ?></div>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac yaxisPanel hidden">
            <?php
              $chart_axis = "yaxis";
              $axis_title = "Left Axis";
            ?>
            <h2 class="ac-header"><div class="ac-trigger"><?php echo $axis_title; ?></div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/chart-axis.php"; ?></div>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac yaxis2Panel hidden">
            <?php
              $chart_axis = "yaxis2";
              $axis_title = "Right Axis";
            ?>
            <h2 class="ac-header"><div class="ac-trigger"><?php echo $axis_title; ?></div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/chart-axis.php"; ?></div>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac minMaxAvgTablePanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Min/Max/Avg Table</div></h2>
            <div class="ac-panel"><?php require "{$this->path}templates/min-max-avg-table.php"; ?></div>
          </div>

        </div>

      </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

    </div>  <!-- .chart-options -->

   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->