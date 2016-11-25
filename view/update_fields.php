<?php

global $wpdb;

$nb_input = $wpdb->get_var("SELECT count(field_id) FROM wp_contact_field");

if($nb_input > 0) { ?>

<form id="modFieldForm" action="../wp-content/plugins/form-contact-angular/traitement/update_fields.php" method="post">

    <h2><?php echo __('Save field', 'contactform'); ?></h2>

    <table class="form-table">

        <thead>
        <tr>
            <th><?php echo __('Field', 'contactform'); ?></th>
            <th><?php echo __('Visibility', 'contactform'); ?></th>
            <th><?php echo __('Placeholder', 'contactform'); ?></th>
            <th><?php echo __('Required field', 'contactform'); ?></th>
            <th><?php echo __('Error message', 'contactform'); ?></th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php

        $sql = "SELECT * FROM wp_contact_field";

        $results = $wpdb->get_results($sql) or die(mysql_error());

        $i = 1;

        foreach( $results as $row ) {

            $id = $row->field_id;
            $name = $row->field_name;
            $placeholder = $row->field_placeholder;
            $err = $row->field_err;
            $display = $row->field_display;
            $view = $row->field_view;
            $req = $row->field_req;

            echo '<tr>';

            echo '<input type="hidden" name="id-field-' . $i . '" value="' . $id . '">';
            echo '<input type="hidden" name="name-' . $i . '" value="' . $name .'">';

            echo '<td scope="row">' . $display . '</td>';

            echo '<td>';

            echo '<input type="radio" name="'.$name.'-view-'.$i.'" value="1"';

            if($view == 1) {
                echo ' checked';
            }
            echo '>';

            echo __('yes', 'contactform');

            echo '<input type="radio" name="'.$name.'-view-'.$i.'" value="0"';

            if($view == 0) {
                echo ' checked';
            }

            echo '>';

            echo __('no', 'contactform');

            echo '</td>';

            echo '<td><input type="text" name="' . $name . '-ph-'.$i.'" value="' . $placeholder . '"></td>';

            echo '<td>';

            echo '<input type="radio" name="'.$name.'-req-'.$i.'" value="1"';

            if($req == 1) {
                echo ' checked';
            }

            echo '>';

            echo __('yes', 'contactform');

            echo '<input type="radio" name="'.$name.'-req-'.$i.'" value="0"';

            if($req == 0) {
                echo ' checked';
            }

            echo '>';

            echo __('no', 'contactform');

            echo '</td>';

            echo '<td><textarea type="text" name="' . $name . '-err-'.$i.'">' . $err . '</textarea></td>';

            echo '<td><a href="../wp-content/plugins/form-contact-angular/traitement/delete_field.php?field_id=' . $id . '&fcn=' . $name . '">';

            echo __('Delete', 'contactform');

            echo '</a></td>';

            echo '</tr>';

            $i++;

        }

        ?>

        <tr class="last-tr">
            <td colspan="6"><input type="submit" value="<?php echo __('Save changes', 'contactform'); ?>" class="button button-primary"></td>
        </tr>

        </tbody>

    </table>

</form>

<?php } ?>