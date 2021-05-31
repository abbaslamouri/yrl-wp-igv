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



        <div class="accordion accordion__level-1 main__Acc">
          
          <div class="ac chartParamsPanel">
            <h2 class="ac-header"><div class="ac-trigger">Chart Parameters</div></h2>
            <div class="ac-panel hidden">
              <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
              
              <div class="field-group">
                <div class="form-group">
                  <button type="button" class="form-group__btn btn button" name="<?php echo "{$this->prefix}__chartParams[mediaUploadBtn]"; ?>" >Upload New File </button>
                  <label for="" class="form-group__label"></label>
                </div>
              </div>

              <div class="field-group">
                <div class="form-group">
                  <input type="text" class="form-group__input chartParam no-hint" name="<?php echo "{$this->prefix}__chartParams[fileUpload]"; ?>" placeholder="Selected File" readonly>
                  <label for="" class="form-group__label">Selected File</label>
                </div>
              </div>
              <div class="field-group">
                <div class="form-group">
                  <input type="text" class="form-group__input chartParam no-hint" name="<?php echo "{$this->prefix}__chartParams[chartId]"; ?>" placeholder="Chart Id"  readonly>
                  <label for="" class="form-group__label">Chart Id</label>
                </div>
              </div>

              <div class="field-group">
                <div class="form-group">
                  <select class="form-group__input form-group__input-select chartParam no-hint" name="<?php echo "{$this->prefix}__chartParams[sheetId]"; ?>" placeholder="Sheet">
                  </select>
                  <label for="" class="form-group__label">Sheet</label>
                </div>
              </div>

              <div class="field-group">
                <div class="form-group">
                  <select class="form-group__input form-group__input-select chartParam" name="<?php echo "{$this->prefix}__chartParams[chartType]"; ?>">
                    <option value="" selected>Select Chart Type</option>
                    <option value="LineChart" selected>Line Chart</option>
                    <option value="ScatterChart" selected>Scatter Chart</option>
                    <option value="PieChart" selected>Pie Chart</option>
                    <option value="BarChart" selected>Bar Chart</option>
                  </select>
                  <label for="" class="form-group__label">Chart Type</label>
                  <div class="form-group__tooltip">
                    <div class="form-group__tooltip-question-mark">?</div>
                    <div class="form-group__tooltip-hint">
                    HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'. 
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </div>
                  </div>
                </div>
              </div>

              <div class="field-group">
                <div class="form-group">
                  <input type="text" name="<?php echo "{$this->prefix}__chartParams[fileId]"; ?>" >
                  <label for="" class="form-group__label">File Id</label>
                </div>
              </div>

            </div>
          </div>

          <div class="ac chartBasicOptionsPanel">
            <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
            <div class="ac-panel">
            </div>
          </div>

          <div class="ac charTracesPanel">
            <h2 class="ac-header"><div class="ac-trigger">Traces</div></h2>
            <div class="ac-panel">
            </div>
          </div>

        </div>

      </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

    </div>  <!-- .chart-options -->

   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->