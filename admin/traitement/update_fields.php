<?php

include_once ("../../include/wp_query.php");

include_once ("../../include/function.php");

try {

    $nb_input = $wpdb->get_var("SELECT count(field_id) FROM wp_contact_field");

    for($i = 1; $i <= $nb_input; $i++) {

        $id = $_POST["id-field-" . $i];

        $name = $_POST["name-" . $i];

        if(isset($_POST[$name . "-view-" . $i])) {

            $wpdb->query($wpdb->prepare("UPDATE wp_contact_field SET
                                field_placeholder = '" . $_POST[$name . "-ph-" . $i] . "',
                                field_view = '" . $_POST[$name . "-view-" . $i] . "',
                                field_req = '" . $_POST[$name . "-req-" . $i] . "',
                                field_err = '" . $_POST[$name . "-err-" . $i] . "'
                                WHERE field_id = %d ", (int) $id));

        }

    }

    $notifText =  __('Form fields have been modified', 'contactform');

    notification("modFieldSuccess", "success", $notifText);

    header("Location:" . $path . "wp-admin/options-general.php?page=plugin-contact-form");
    exit;

}

catch (Exception $e) {

    $notifText =  __('Form fields have not been modified', 'contactform');

    notification("modFieldSuccess", "error", $notifText);

    header("Location:" . $path . "wp-admin/options-general.php?page=plugin-contact-form");
    exit;

}


