<!-- Create a header in the default WordPress 'wrap' container -->
<div class="edit-chart hidden">

  <!-- Content wrapper -->
  <div class="edit-chart__content">
    <!-- Admin messages -->
    
    <!-- <div class="hint-popup">kkkkkkkk</div> -->

    <!-- Show module title -->
    <div class="edit-chart__header">
      <div class="page-title">
        <h2><?php echo esc_html(get_admin_page_title()) ?></h2>
        <a class="button-secondary btn" id="<?php echo"{$this->prefix}__cancelChart"; ?>" href="<?php echo add_query_arg(array("page" => $this->prefix), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Go Back' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
      </div>
      <div class="edit-chart__admin-messages"></div>

      <!-- <div class="edit-chart__close-chart">&times;</div>  -->
    </div>

    <div class="edit-chart__body">

      <div class="edit-chart__chart-view">

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

      <div class="edit-chart__chart-options">

        <!-- Create the form that will be used to render our options -->
        <form id="<?php echo "{$this->prefix}__chartOptionsForm" ?>" name="<?php echo "{$this->prefix}__chartOptionsForm" ?>" novalidate action="javascript:void(0);">

          <div class="accordion accordion__level-1 main__Accordion">
            
            <div class="ac fileUploadAc">
              <h2 class="ac-header"><div class="ac-trigger">Chart Params</div></h2>
              <div class="ac-panel">
                <?php require "{$this->path}templates/params.php"; ?>
              </div>
            </div>

            <div class="ac basicOptionsAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
              <div class="ac-panel">
                <?php //require "{$this->path}templates/basic-options.php"; ?>
              </div>
            </div>

            <div class="ac titleAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Title</div></h2>
              <div class="ac-panel"></div>
            </div>

            <div class="ac legendAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Legend</div></h2>
              <div class="ac-panel"></div>
            </div>

            <div class="ac hoverlabelAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Hover Label</div></h2>
              <div class="ac-panel"></div>
            </div>

            <div class="ac gridAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Grid</div></h2>
              <div class="ac-panel"></div>
            </div>

            <div class="ac modebarAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Modebar</div></h2>
              <div class="ac-panel"></div>
            </div>

            <div class="ac tracesAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Traces</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Trace options go here. </p>
                <div class="accordion accordion__level-2 traces__Accordion"></div>
              </div>
            </div>

            <div class="ac annotationsAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Annotations</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                <a href="#"  class="button button-primary" id="<?php echo "{$this->prefix}__addAnnotation"; ?>" >Add New</a>
                <div class="accordion accordion__level-2 annotations__Accordion">
                </div>
              </div>
            </div>

            <div class="ac xaxesAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">X Axes</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                <a href="#"  class="button button-primary" id="<?php echo "{$this->prefix}__addNewXAxis"; ?>" >Add New X-Axis</a>
                <div class="accordion accordion__level-2 xaxes__Accordion">
                </div>
              </div>
            </div>

            <div class="ac yaxesAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Y Axes</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                <a href="#"  class="button button-primary" id="<?php echo "{$this->prefix}__addNewYAxis"; ?>" >Add New Y-Axis</a>
                <div class="accordion accordion__level-2 yaxes__Accordion">
                </div>
              </div>
            </div>

            <!-- <div class="ac xaxisAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Bottom Axis</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Bottom Axis Options go here.</p>
                <div class="accordion accordion__level-3 xaxis__Accordion"></div>
              </div>
            </div>

            <div class="ac xaxis2Ac hidden">
              <h2 class="ac-header"><div class="ac-trigger">Top Axis</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Top Axis Options go here.</p>
                <div class="accordion accordion__level-3 xaxis2__Accordion"></div>
              </div>
            </div>

            <div class="ac yaxisAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Left Axis</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Left Axis Options go here.</p>
                <div class="accordion accordion__level-3 yaxis__Accordion"></div>
              </div>
            </div>

            <div class="ac yaxis2Ac hidden">
              <h2 class="ac-header"><div class="ac-trigger">Right Axis</div></h2>
              <div class="ac-panel">
                <p class="ac-text intro">Right Axis Options go here.</p>
                <div class="accordion accordion__level-3 yaxis2__Accordion"></div></div>
            </div> -->

            <div class="ac minMaxAvgTableAc hidden">
              <h2 class="ac-header"><div class="ac-trigger">Min/Max/Avg Table</div></h2>
              <div class="ac-panel"></div>
            </div>

          </div>

        </form>

        <!-- Accordion will be injected here -->
        <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

      </div>  <!-- .chart-options -->

    </div>

    <!-- <div class="edit-chart__footer">
      footer
    </div> -->

  </div> <!-- // END <div class="edit-chart__content"> -->

</div> <!-- // END <div class="edit-chart" -->