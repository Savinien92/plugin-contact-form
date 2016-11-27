<?php

include_once ("../../include/wp_query.php");

include_once ("../../include/function.php");

try {

    function suppr_accents($str, $encoding='utf-8')
    {
        // transformer les caractères accentués en entités HTML
        $str = htmlentities($str, ENT_NOQUOTES, $encoding);

        // remplacer les entités HTML pour avoir juste le premier caractères non accentués
        // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
        $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

        // Remplacer les ligatures tel que : Œ, Æ ...
        // Exemple "Å“" => "oe"
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        // Supprimer tout le reste
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }

    $name = mb_strtolower(str_replace(" ", "_", $_POST["name"]));

    $name = suppr_accents($name);

    $wpdb->query(
        $wpdb->prepare("
                  INSERT INTO wp_contact_field
                      (field_name, field_type, field_tagname, field_display, field_placeholder, field_view, field_req, field_err)
                  VALUES
                      (%s, %s, %s, %s, %s, %d, %d, %s)", $name, $_POST["type"], $_POST["tagname"], $_POST["name"], $_POST["placeholder"], 0, 0, ''
        )
    );

    // Creation de la colonne dans wp_contact

    if($_POST["tagname"] == "input") {

        $type = "VARCHAR(150)";

    }

    if($_POST["tagname"] == "textarea") {

        $type = "TEXT";

    }

    $sql = "ALTER TABLE wp_contact ADD contact_".$name." ".$type;

    $wpdb->query($sql);

    $notifText =  __('The form field did not add', 'contactform');

    notification("addFieldSuccess", "success", $notifText);

    // Redirection

    header("Location:" . $path . "wp-admin/options-general.php?page=contact_form_angular");
    exit;

}

catch (Exception $e) {

    $notifText =  __('The form field has not been added', 'contactform');

    notification("addFieldSuccess", "error", $notifText);

    header("Location:" . $path . "wp-admin/options-general.php?page=contact_form_angular");
    exit;

}