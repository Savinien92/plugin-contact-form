<?php

if (!isset($_SESSION["msgSend"])) {

?>

<div id="modal" class="no-visible">
    <div id="modalContent">
        <div id="modalContentHeader">
            <h2 class="widget-title"><?php echo __('Contact us !', 'contactform'); ?><i class="em em-rocket"></i></h2>
            <h2><div id="close"><i class="em em-cool"></i></div></h2>
        </div>
        <div ng-app="mainApp" ng-controller="formController">
            <form id="angularForm" name="angularForm" method="POST" action="wp-content/plugins/form-contact-angular/admin/traitement/insert_form.php">

                <?php

                global $wpdb;

                $sql = "SELECT * FROM wp_contact_field WHERE field_view=1";

                $results = $wpdb->get_results($sql) or die(mysql_error());

                foreach( $results as $row ) {

                    $type = $row->field_type;
                    $name = $row->field_name;
                    $placeholder = $row->field_placeholder;
                    $req = $row->field_req;
                    $err = $row->field_err;
                    $tagname = $row->field_tagname;

                    if ($tagname == "input") {

                        echo '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" ng-model="' . $name . '"';

                        if ($req == 1) {

                            echo ' required';

                        }

                        echo '>';

                        if ($req == 1) {

                            echo '<div ng-show="angularForm.$submitted || angularForm.' . $name . '.$touched">';

                            echo '<div class="alert" ng-show="angularForm.' . $name . '.$error.required">' . $err . '</div>';

                            echo '</div>';

                        }

                    }

                    if ($tagname == "textarea") {

                        echo '<textarea name="' . $name . '" placeholder="' . $placeholder . '" ng-model="' . $name . '"';

                        if ($req == 1) {

                            echo ' required';

                        }

                        echo '></textarea>';

                        if ($req == 1) {

                            echo '<div ng-show="angularForm.$submitted || angularForm.' . $name . '.$touched">';

                            echo '<div class="alert" ng-show="angularForm.' . $name . '.$error.required">' . $err . '</div>';

                            echo '</div>';

                        }

                    }

                }

                $sql = "SELECT field_name FROM wp_contact_field WHERE field_view=1 and field_req=1";

                $results = $wpdb->get_results($sql);

                if (empty($results) === true) {

                    echo '<input type="submit" value="Send" ng-model="submit">';

                } else {

                    $ngDesabled = "";

                    foreach ( $results as $row ) {

                        $ngDesabled = $ngDesabled . "!" . $row->field_name . " || ";

                    }

                    $ngDesabled = substr($ngDesabled, 0, -4);

                    echo '<input type="submit" value="Send" ng-model="submit" ng-disabled="' . $ngDesabled . '">';

                }

                echo '<div class="not-allowed">Humm .. missing things</div>';

                ?>

            </form>
        </div>
    </div>
</div>

<?php

} else {

    ?>

    <div id="modal" class="is-visible">
        <div id="modalContent">
            <div id="modalContentHeader">
                <h3 class="widget-title"><div class="close"><?php echo __('Votre demande de contact à bien été envoyé', 'contactform'); ?><i class="em em---1"></i></div></h3>
                <!--                        <div id="close" style="top: auto; bottom: 20px">--><?php //echo __('Close', 'contactform'); ?><!--</div>-->
            </div>
        </div>
    </div>

    <?php

    unset($_SESSION["msgSend"]);

}

?>