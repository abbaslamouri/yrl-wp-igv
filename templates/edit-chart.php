<!-- Create a header in the default WordPress 'wrap' container -->
 <div class='<?php echo $this->prefix; ?>'>


 
  <!-- Show module title -->
  <div class="admin-heading">
    <h2 >Chart</h2>
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Go Back' ); ?>"><?php esc_attr_e( 'Cancel' ); ?></a>
  </div>

  <!-- <div class="container" style="width:100%; height:100%; border:1px red solid;">
    <canvas id="color-picker" class="" style="border:3px solid rgba(15,15,15,0.2);"></canvas>
    <div class="info">
      <h3>Selected Color</h3>
      <div class="selected" style="width:50px; height:50px; border-radius:100%; border:3px solid rgba(15,15,15,0.2);"></div>
    </div>
  </div> -->


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

          <div class="accordion">
            <?php foreach ( $payload["chart"] as $element ) :?>
              <?php if ($element["panel"]["id"] !== "{$this->prefix}__chartConfigPanel") :?>
                <div class="accordion__toggle hidden">
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

          <!-- Accordion will be injected here -->
          <button class="button button-primary hidden" id="<?php echo "{$this->prefix}__saveChart"; ?>" name="<?php echo "{$this->prefix}__saveChart"; ?>" disabled>Save Chart</button>

        </form>

      </div>  <!-- .chart-options -->

    </div>
   
  </div> <!-- // END <div class = "content-wrapper"> -->

 

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->