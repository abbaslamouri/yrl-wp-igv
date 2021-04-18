<table class="<?php echo empty($payload) ? 'hidden' :''; ?>" id='iwpgv__file-list'>
  <thead>
    <tr>
      <th>File ID</th><th width="40%">File Name</th><th>Sheets</th><th>Charts</th></tr>
  </thead>
  <tbody>
    <?php
    foreach ($payload as $file_id => $file) :
      
      $chart_count = 0;
      foreach ($this->charts as $chart_id => $chart) {
        if ($chart['chartParams']['fileId'] == $file_id) {
          $chart_count++;
        }
      }
      ?>
      <tr>
        <td><?php echo $file_id; ?></td>
        <td><?php echo $file['fileName']; ?></td>
        <td><?php echo $file['sheetCount']; ?></td>
        <td><?php echo $chart_count; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
