<?php

foreach (get_pages() as $page) {

  $selected = false;
  if ($field['value'] && $field['value'] == $page->ID) {
    $selected = $page->ID;
    break;
  }

}

// Display select input field
$page_args = array(
  'depth'                 => 1,  // show only top level pages
  'selected'              => $selected,
  'id'                    => $field_id,  // input id attribute
  'name'                  => $field['tab']."[".$field_id."]", // input name attribute
  'show_option_none'      => $field['option_none'], // selec t none
  'option_none_value'     => null, // select none value
);
wp_dropdown_pages($page_args);

?>
<span class = "description"><?php echo $field['description'] ?></span>
<?php
