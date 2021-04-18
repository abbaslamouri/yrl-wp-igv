<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?>'>

  <!-- Show module title -->
  <div class="admin-heading">
    <h2 >Chart</h2>
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->plugin), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Cancel' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
  </div>

  <!-- Admin messages -->
  <div class="admin-messages"></div>

  <!-- Content wrapper -->
  <div class="content-wrapper chart">

    <div class="chart-view">

      <!--Dashboard divs-->
      <div id="iwpgv__dashboard" class='hidden'>
        <div class="num-range-min-max">
          <div id="iwpgv__num-range-filter"></div>
          <div id="iwpgv__min-max-table-chart"></div>
        </div>
        <div id="iwpgv__table-chart"></div>
        <div id="iwpgv__chart"></div>
      </div>

      <!-- Loading spinner -->
      <img id = "iwpgv__spinner" class="loading-spinner hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">

      <!-- Warning heading -->
      <h3 id="iwpgv__warning" class="warning">To view chart, you must select/upload a file, select a sheet, and chart type</h3>

    </div>

    <div class="chart-options">

      <!-- Create the form that will be used to render our options -->
      <form id="iwpgv__options" action='options.php' method='post'>

        <div class="chart-params-panels accordion">

          <div class="panel-heading accordion__toggle">
            <div class="accordion__heading">File Upload</div>
            <svg class ="accordion__svg">
              <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-keyboard_arrow_down"; ?>"></use>
            </svg>
          </div>
          <div class = "panel accordion__content accordion active visible" id = "iwpgv__fileUploadPanel" >
            <div class="panel-intro">
              Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).
            </div>

            <div class="field-group dual-field">
              <div class="form-group ">
                <div class="form-group__field">
                  <input type="text" class = "form-group__input chartParam" id="iwpgv__fileUpload" name="iwpgv__fileUpload" value = "" />
                </div>
              </div>
              <div class="form-group ">
                <div class="form-group__field">
                  <input type="button" class="button-secondary form-group__input" value="Upload New File" id="iwpgv__media-upload-btn" />
                </div>
              </div>
            </div>

            <div class="field-group dual-field">
              <div class="form-group ">
                <label class="form-group__label" for="iwpgv__enableSeries">
                  <?php _e("Enable Series", $this->plugin); ?>
                </label>
                <div class="form-group__field">
                  <input type="checkbox" class = "form-group__input chartParam" id="iwpgv__enableSeries" name="iwpgv__enableSeries" />
                </div>
              </div>
              <div class="form-group ">
                <label class="form-group__label" for="iwpgv__enableTrendlines">
                  <?php _e("Enable Trendlines", $this->plugin); ?>
                </label>
                <div class="form-group__field">
                  <input type="checkbox" class = "form-group__input chartParam" id="iwpgv__enableTrendlines" name="iwpgv__enableTrendlines" />
                </div>
              </div>
            </div>

            <div class="field-group dual-field">
              <div class="form-group ">
                <label class="form-group__label" for="iwpgv__enableNumRangeSlider">
                  <?php _e("Enable Number Range Slider", $this->plugin); ?>
                </label>
                <div class="form-group__field">
                  <input type="checkbox" class = "form-group__input chartParam" id="iwpgv__enableNumRangeSlider" name="iwpgv__enableNumRangeSlider" />
                </div>
              </div>
              <div class="form-group ">
                <label class="form-group__label" for="iwpgv__enableMinMaxTableChart">
                  <?php _e("Enable Min/Max/Avg Table", $this->plugin); ?>
                </label>
                <div class="form-group__field">
                  <input type="checkbox" class = "form-group__input chartParam" id="iwpgv__enableMinMaxTableChart" name="iwpgv__enableMinMaxTableChart" />
                </div>
              </div>
            </div>

            <div class="field-group single-field">
              <div class="form-group ">
								<label class="form-group__label" for="iwpgv__sheetId"><?php _e("Sheet", $this->plugin); ?></label>
                <div class="form-group__field">
                  <select class = "form-group__select chartParam" id = "iwpgv__sheetId" name = "iwpgv__sheetId" >
                    <!-- <option value="" selected >Select Sheet</option> -->
                  </select>
								  <span class="form-group__hint hidden">hint for sheet</span>
							  </div>
              </div>
            </div>

            <div class="field-group single-field">
              <div class="form-group ">
								<label class="form-group__label" for="iwpgv__chartType"><?php _e("Chart Type", $this->plugin); ?></label>
                <div class="form-group__field">
                  <select class = "form-group__select chartParam" id = "iwpgv__chartType" name = "iwpgv__chartType" >
                    <option value="">Select Chart Type</option>
                    <?php foreach ($this->chart_types as $key => $value)  :?>
                      <option value="<?php echo $key; ?>" ><?php echo esc_html__($value, $this->plugin); ?></option>
                    <?php endforeach; ?>
                  </select>
								  <span class="form-group__hint hidden">hint for sheet</span>
							  </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="iwpgv__chartId" name="iwpgv__chartId" value="">
          <!-- <input type="hidden" id="iwpgv__fileId" name="iwpgv__fileId" value=""> -->

        </div>

        <div class="chart-options-panels accordion">

          <?php 
          if (!empty($payload)) {
            echo $this->render_panels($payload);
          } 
          ?>

        </div>
        <button class = 'button button-primary' id ='iwpgv__save-chart' name = 'iwpgv__save-chart' disabled>Save Chart</button>

      </form>

    </div>  <!-- .content-wrapper -->
   
  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->