<?php

global $wpdb;

$nb_daata = $wpdb->get_var("SELECT count(contact_id) FROM wp_contact");

if($nb_data > 0) { ?>

<h2>Télécharger les données</h2>

<a href="#">Télécharger au format Exel</a>

<?php } ?>