<!-- Create a header in the default WordPress 'wrap' container -->
<?php 
  // $layout = $payload["chart"]["layout"];
  // $__layout = "{$this->prefix}__layout";

  // $config = $payload["chart"]["config"];
  // $__config = "{$this->prefix}__config";

  // $min_max_avg_table = $payload["chart"]["minMaxAvgTable"];
  // $__min_max_avg_table = "{$this->prefix}__minMaxAvgTable";
  // print_r($payload)
//  print_r($layout)


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
  <div class="hint-popup"></div>

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

        <div class="accordion accordion__level-1 main__Accordion">
          
          <div class="ac fileUploadAc">
            <h2 class="ac-header"><div class="ac-trigger">File Upload</div></h2>
            <div class="ac-panel hidden">
              <?php require "{$this->path}templates/file-upload.php"; ?>
            </div>
          </div>

          <div class="ac layoutAc">
            <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
            <div class="ac-panel">
              <?php //require "{$this->path}templates/basic-options.php"; ?>
            </div>
          </div>

          <div class="ac titleAc">
            <h2 class="ac-header"><div class="ac-trigger">Title</div></h2>
            <div class="ac-panel"></div>
          </div>

          <div class="ac legendAc hidden">
            <h2 class="ac-header"><div class="ac-trigger">Legend</div></h2>
            <div class="ac-panel"></div>
          </div>


          <!-- <div class="ac configPanel hidden">
            <h2 class="ac-header"><div class="ac-trigger">Chart Config.</div></h2>
            <div class="ac-panel"><?php //require "{$this->path}templates/chart-config.php"; ?></div>
          </div> -->

          <div class="ac tracesAc hidden">
            <h2 class="ac-header"><div class="ac-trigger">Traces</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-2 traces__Accordion"></div>
            </div>
          </div>

          <div class="ac annotationsAc">
            <h2 class="ac-header"><div class="ac-trigger">Annotations</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-2 annotations__Accordion">
              <button class="button button-primary" id="<?php echo "{$this->prefix}__addAnnotation"; ?>" name="<?php echo "{$this->prefix}____addAnnotation"; ?>" >Add New</button>
              </div>
            </div>
          </div>

          <div class="ac hoverlabelAc hidden">
            <h2 class="ac-header"><div class="ac-trigger">Hoverlabel</div></h2>
            <div class="ac-panel"></div>
          </div>

          <div class="ac modebarAc hidden">
            <h2 class="ac-header"><div class="ac-trigger">Modebar</div></h2>
            <div class="ac-panel"><?php //require "{$this->path}templates/modebar.php"; ?></div>
          </div>

          <div class="ac xaxisAc">
            <h2 class="ac-header"><div class="ac-trigger">Bottom Axis</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-3 xaxis__Accordion"></div>
            </div>
          </div>

          <div class="ac xaxis2Ac">
            <h2 class="ac-header"><div class="ac-trigger">Top Axis</div></h2>
            <div class="ac-panel"></div>
          </div>

          <div class="ac yaxisAc">
            <h2 class="ac-header"><div class="ac-trigger">Left Axis</div></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              <div class="accordion accordion__level-3 yaxis__Accordion"></div>
            </div>
          </div>

          <div class="ac yaxis2Ac">
            <h2 class="ac-header"><div class="ac-trigger">Right Axis</div></h2>
            <div class="ac-panel"></div>
          </div>

          <div class="ac minMaxAvgTableAc hidden">
            <h2 class="ac-header"><div class="ac-trigger">Min/Max/Avg Table</div></h2>
            <div class="ac-panel"><?php //require "{$this->path}templates/min-max-avg-table.php"; ?></div>
          </div>

        </div>

      </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

    </div>  <!-- .chart-options -->

   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->