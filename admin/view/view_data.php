<?php

    global $wpdb;

    $sql = "SHOW COLUMNS FROM wp_contact";

    $results = $wpdb->get_results($sql);

    foreach( $results as $row ) {

       $res[] = $row->Field;

    }

    $res = array_splice($res, 1);

    $col = array_merge($res);

    $result = str_replace("contact_", "", $col);

    $sql = "SELECT " . implode(',', $col) . " FROM wp_contact";

    $values = $wpdb->get_results($sql);

    $nb_data = $wpdb->get_var("SELECT count(contact_id) FROM wp_contact");

    if($nb_data > 0) { ?>

        <h2><?php echo __('Data table', 'contactform'); ?></h2>

        <table class="form-table">

            <thead>

                <tr>
                    <?php

                    foreach( $result as $row ) {

                        echo "<th>" . ucfirst($row) . "</th>";

                    }

                    ?>

                </tr>

            </thead>

            <tbody>
                <?php

                foreach( $values as $row ) {

                    echo "<tr>";

                    for ($i = 0; $i < sizeof($col); $i++) {

                        echo "<td>";

                        echo $val[$i] = $row->$col[$i];

                        echo "</td>";

                    }

                    echo "</tr>";

                }

                ?>
            </tbody>

        </table>

    <?php } ?>
