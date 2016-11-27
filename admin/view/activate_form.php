<?php

    global $wpdb;

    $sql = "SELECT * FROM wp_contact_form WHERE form_id=1";

    $value = $wpdb->get_results($sql);

    foreach ( $value as $row ) {
        $activate[] = $row->form_activate;
    }

    $value = $activate[0];

?>

<div id="divActivate">

    <h2><?php echo __('Activate or desactivate form', 'contactform'); ?></h2>

    <button id="activate" data-value="<?php if($value == 0) { echo "activate";} else { echo "desactivate";} ?>" class="button button-large <?php if($value == 0) { echo "activate";} else { echo "desactivate";} ?>"><?php if($value == 0) { echo __('Activate', 'contactform'); } else { echo __('Desactivate', 'contactform');} ?></button>

</div>