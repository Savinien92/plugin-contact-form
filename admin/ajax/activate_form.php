<?php

include_once ("../../include/wp_query.php");

try {

    $sql = "SELECT * FROM wp_contact_form WHERE form_id=1";

    $value = $wpdb->get_results($sql);

    foreach ( $value as $row ) {
        $activate[] = $row->form_activate;
    }

    $value = $activate[0];

    if ($value == 1) {


        $value = 0;

    } else {

        $value = 1;

    }

    $wpdb->query("UPDATE wp_contact_form SET
                        form_activate = " . $value . "
                        WHERE form_id = 1");

}

catch(Exception $e) {

    die("Connection à MySQL impossible : " . $e->getMessage());

}
