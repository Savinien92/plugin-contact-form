<?php

include_once ("../../include/wp_query.php");

try {

    // Recherche des colonnes en bd
    $sql = "SHOW COLUMNS FROM wp_contact";
    $results = $wpdb->get_results($sql);
    foreach( $results as $row ) {
        $col[] = $row->Field;
    }

// Traitement des données
// Supprimer contact_id et contact_date
    $result = array_splice($col, 1);
    $result = array_merge($result);


// Recherche des fields non visible
    $sql = "SELECT field_name FROM wp_contact_field WHERE field_view=0";
    $notView = $wpdb->get_results($sql);

// Compteur des fields non visible
    $sql = "SELECT count(field_id) FROM wp_contact_field WHERE field_view=1";
    $nb_notView = $wpdb->get_var($sql);

// Recherche des fields non required
    $sql = "SELECT field_name FROM wp_contact_field WHERE field_req=0";
    $notReq = $wpdb->get_results($sql);

//echo $nb_notView;

// Si il existe des fields non visible
    if ($nb_notView > 0) {

        $hideField = array();
        foreach ( $notView as $row ) {
            $hideField[] = $row->field_name;
        }

        foreach ( $notReq as $row ) {
            $notReqField[] = $row->field_name;
        }

//    var_dump($hideField);
//    var_dump($notReqField);
    }



    for ($i = 0; $i < sizeof($result); $i++) {

        $values[] = str_replace('contact_', '', $result[$i]);

    }



    if ($nb_notView > 0) {

        for ($j = 0; $j < sizeof($values); $j++) {

            for ($k = 0; $k < sizeof($hideField); $k++) {

                if($values[$j] == $hideField[$k]) {

                    $values[$j] = '\'\'';

                }
            }
        }

        for ($l = 0; $l < sizeof($values); $l++) {

            if(isset($_POST[$values[$l]])) {

                $values[$l] = "'" . $_POST[$values[$l]] . "'";
            }
        }
    }



//var_dump($result);
//var_dump($values);

    $values[0] = "'" . date("Y-m-d H:i:s") . "'";
    $values = implode(", ", $values);
    $fields = implode(", ", $result);



    $sql = "INSERT INTO wp_contact ($fields) VALUES ($values)";

//echo $sql;
//die;

    $query = $wpdb->query($sql);

    if(!isset($_SESSION["msgSend"])){

        $_SESSION["msgSend"] = "ok";

    }

    // Redirection

    header("Location:../../../../../index.php");
}

catch (Exception $e) {

    if(!isset($_SESSION[$valSession])){

        $_SESSION[$valSession] = "nok";

    }

    header("Location:../../../../../index.php");

}
