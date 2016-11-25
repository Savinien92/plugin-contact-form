<?php

include_once ("../include/wp_query.php");

include_once ("../include/function.php");

try {

    $id = $_GET["field_id"];
    $nameCol = $_GET["fcn"];

    $wpdb->query(
        $wpdb->prepare("DELETE FROM wp_contact_field WHERE field_id = %d", (int) $id
        )
    );

    $wpdb->query("ALTER TABLE wp_contact DROP contact_" . $nameCol);

    $nb_max_id = $wpdb->query("SELECT MAX(field_id) + 1 FROM wp_contact_field");

    $wpdb->query("ALTER TABLE wp_contact_field AUTO_INCREMENT = " . $nb_max_id);

    $notifText =  __('The form field has been removed', 'contactform');

    notification("delFieldSuccess", "success", $notifText);

    // Redirection

    header("Location:" . $path . "wp-admin/options-general.php?page=contact_form_angular");
    exit;

}

catch (Exception $e) {

    $notifText =  __('The form field has not been deleted', 'contactform');

    notification("delFieldSuccess", "error", $notifText);

    header("Location:" . $path . "wp-admin/options-general.php?page=contact_form_angular");
    exit;

}