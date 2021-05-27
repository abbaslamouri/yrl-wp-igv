<!-- Create a header in the default WordPress 'wrap' container -->
 <div class="<?php echo "{$this->prefix}__admin"; ?>">
 
  <!-- Show module title -->
  <div class="admin-heading">
    <h2 >Chart</h2>
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Go Back' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
  </div>

  <!-- Admin messages -->
  <div class="admin-messages"></div>

  <!-- Content wrapper -->
  <div class="content-wrapper edit-chart">


    <div class="chart-view">

      <!-- Loading spinner -->
      <img class="spinner hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
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



        <div class="accordion-1">
          
          <div class="ac chartParamsPanel">
            <h2 class="ac-header"><button class="ac-trigger">Chart Parameters</button></h2>
            <div class="ac-panel">
              <p class="ac-text intro">This where you can upload a new file, select a sheet and a chart type</p>
              
              <div class="field-group hundred-zero">
                <div class="form-group">
                  <input type="button" class="form-group__input button btn" name="<?php echo "{$this->prefix}__chartParams[mediaUploadBtn]"; ?>" value="Upload New File">
                  <label for="" class="form-group__label"></label>
                </div>
              </div>

              <div class="field-group fifty-fifty">
                <div class="form-group">
                  <input type="text" class="form-group__input chartParam" name="<?php echo "{$this->prefix}__chartParams[fileUpload]"; ?>" placeholder="Selected File" readonly>
                  <label for="" class="form-group__label">Selected File</label>
                </div>
                <div class="form-group">
                  <input type="text" class="form-group__input chartParam" name="<?php echo "{$this->prefix}__chartParams[chartId]"; ?>" placeholder="Chart Id"  readonly>
                  <label for="" class="form-group__label">Chart Id</label>
                </div>
              </div>

              <div class="field-group hundred-zero">
                <div class="form-group">
                  <select class="form-group__input chartParam" name="<?php echo "{$this->prefix}__chartParams[sheetId]"; ?>" placeholder="Sheet">
                    <!-- <option value="" selected>Select Sheet</option> -->
                  </select>
                  <label for="" class="form-group__label">Sheet</label>
                </div>
              </div>

              <div class="field-group hundred-zero">
                <div class="form-group">
                  <select class="form-group__input chartParam" name="<?php echo "{$this->prefix}__chartParams[chartType]"; ?>">
                    <option value="" selected>Select Chart Type</option>
                    <option value="LineChart" selected>Line Chart</option>
                    <option value="ScatterChart" selected>Scatter Chart</option>
                    <option value="PieChart" selected>Pie Chart</option>
                    <option value="BarChart" selected>Bar Chart</option>
                  </select>
                  <label for="" class="form-group__label">Chart Type</label>
                </div>
              </div>

              <div class="field-group">
                <div class="form-group">
                  <input type="text" class="form-group__input chartParam" name="<?php echo "{$this->prefix}__chartParams[fileId]"; ?>" readonly>
                  <label for="" class="form-group__label">File Id</label>
                </div>
              </div>

            </div>
          </div>

          <div class="ac basicOptionsPanel">
            <h2 class="ac-header"><button class="ac-trigger">Basic Options</button></h2>
            <div class="ac-panel">
              <p class="ac-text intro">Here you can modify the plot basic options</p>
            </div>
          </div>

        </div>

      </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

    </div>  <!-- .chart-options -->

   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->