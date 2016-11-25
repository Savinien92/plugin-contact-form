<?php


/**
*Plugin Name: Angular Form Popup
*Plugin URI:
*Description: Ce plugin met en place un formulaire de contact avec enregistrement en base de donnée (le nom de la table est: wp_contact). Ce formulaire de contact est de type modal et utilise la technologie Angular.js pour une vérification en temps réel des données saisis par l'utilisateur.
ATTENTION: Lors de la désactivation du plugin, celui-ci supprime la table wp_contact.
*Author: Savinien MAIRE
*Version: 1.1
*Author URI:
*Text Domain: contact-form
*/


// ------------------------- GENERALE -------------------------

//define( 'ABSPATH' ) or die ( 'No direct load !' );

session_start();



function traduction() {
    load_plugin_textdomain('contactform', false, dirname(plugin_basename( __FILE__)) . '/languages');
}
add_action('plugins_loaded', 'traduction');


// ------------------------- BACK-OFFICE -------------------------

/**
 * Savoir si le formulaire est activé ou non
 * @author Savinien Maire
 */


function form_activate() {

    global $wpdb;

    $sql = "SELECT * FROM wp_contact_form WHERE form_id=1";

    $value = $wpdb->get_results($sql);

    foreach ( $value as $row ) {
        $activate[] = $row->form_activate;
    }

    $value = $activate[0];

    return $value;
}

/**
 * Add Angular.js link to front ans back office
 * @author Savinien Maire
 */

if(form_activate() == 1) {

    function link_angular()
    {

        echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>';

    }

    add_action('wp_head', 'link_angular');
}


/**
 * Create table 'wp_contact' to database
 * @author Savinien Maire
 */

function create_table_contact() {

    global $wpdb;

    $nom_table_contact = $wpdb->prefix . 'contact';

    $sql = "CREATE TABLE IF NOT EXISTS $nom_table_contact (
              contact_id bigint(20) unsigned NOT NULL auto_increment,
              contact_date datetime NOT NULL,
              PRIMARY KEY (contact_id))";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);

}
register_activation_hook( __FILE__, 'create_table_contact');


/**
 * Create table 'wp_contact_field' to database
 * @author Savinien Maire
 */

function create_table_contact_field() {

    global $wpdb;

    $nom_table_contact = $wpdb->prefix . 'contact_field';

    $sql = "CREATE TABLE IF NOT EXISTS $nom_table_contact (
              field_id bigint(20) unsigned NOT NULL auto_increment,
              field_name VARCHAR(100) NOT NULL,
              field_type VARCHAR(50) NOT NULL,
              field_tagname VARCHAR(10) NOT NULL,
              field_display VARCHAR(100) NOT NULL,
              field_placeholder VARCHAR(100),
              field_view INT(1) NOT NULL,
              field_req INT(1) NOT NULL,
              field_err VARCHAR(200),
              PRIMARY KEY (field_id))";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);

}
register_activation_hook( __FILE__, 'create_table_contact_field');


/**
 * Create table 'wp_contact_form' to database
 * @author Savinien Maire
 */

function create_table_activate() {

    global $wpdb;

    $nom_table_contact = $wpdb->prefix . 'contact_form';

    $sql = "CREATE TABLE IF NOT EXISTS $nom_table_contact (
              form_id bigint(20) unsigned NOT NULL auto_increment,
              form_name VARCHAR (100) NOT NULL,
              form_activate int(1) NOT NULL,
              PRIMARY KEY (form_id))";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);

    $sql = "INSERT INTO wp_contact_form (form_name, form_activate) VALUES ('Formulaire de contact', 0)";

    $wpdb->query($sql);

}
register_activation_hook( __FILE__, 'create_table_activate');

/**
 * Add form setting js to back office
 * @author Savinien Maire
 */

function form_angular_admin_js() {

    wp_enqueue_script( 'form-contact-admin', esc_url( plugins_url( 'webroot/js/form-contact-admin.js', __FILE__ )), array(), '1.0', true );

}
add_action( 'admin_enqueue_scripts', 'form_angular_admin_js' );

/**
 * Add css file to front office
 * @author Savinien Maire
 */

function insert_css_admin_head() {

    // Ajout du css dans le Header
    wp_register_style('form-contact-angular-css-admin', esc_url( plugins_url('webroot/style/form-contact-admin.css', __FILE__ )));
    wp_enqueue_style('form-contact-angular-css-admin');

}
add_action('admin_enqueue_scripts', 'insert_css_admin_head');

/**
 * Add menu setting contact to back office
 * @author Savinien Maire
 */

function my_plugin_menu() {

    add_options_page( 'Contact settings', 'Contact', 'manage_options', 'contact_form_angular', 'my_plugin_callback' );

}

/**
 * Settings page to back office
 * @author Savinien Maire
 */

function my_plugin_callback() {

    ?>

    <div id="formContactAngular" class="wrap">
        <h1><?php echo __('Contact Form Settings', 'contactform'); ?></h1>

        <div class="dashboard-widgets-wrap">
            <div class="metabox-holder">

                <div class="flat-card">
                    <p><?php echo __('You can choose the fields present in your contact form, and also make them mandatory or not', 'contactform'); ?></p>
                </div>

                <?php

                include_once ("include/notifications.php");

                include_once ("view/activate_form.php");

                include_once ("view/insert_field.php");

                include_once ("view/update_fields.php");

                include_once ("view/view_data.php");

                include_once ("include/notifications_unset.php");

                ?>

            </div>
        </div>
    </div>

<?php
}
add_action( 'admin_menu', 'my_plugin_menu' );

/**
 * Add widget
 * @author Savinien Maire
 */

if (form_activate() == 1) {

    if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        // PHP 5.3+ seulement
        add_action('widgets_init', function () {
            register_widget('Widget_contact_link');
        });
    } elseif (version_compare(PHP_VERSION, '5.2.0') >= 0) {
        // PHP 5.2+
        add_action('widgets_init', create_function('', 'return register_widget("Widget_contact_link");'));
    } else {
        // Petit message si la version PHP est inférieure à 5.2.0
        echo __('Votre version PHP est', 'texte-domaine') . ' ' . phpversion() . '. ';
        echo __('Besoin 5.2.0+. Merci de faire une mise à jour.', 'texte-domaine');
    }


    class Widget_contact_link extends WP_Widget {

        function __construct() {
            parent::__construct(
                'social-widget',
                __( 'Contact link', 'contact-form' ),
                array(
                    'description' => __( 'Move this widget to the sidebar view the form link', 'contact-form' ),
                )
            );
        }

        public function widget($args, $instance) {

            echo $args['before_widget'];

            ?>

            <div class="textwidget">

                <div id="contactUs">

                    <a href="#" id="contactUsLink"><?php echo __('Contact us', 'contactform'); ?></a>

                </div>

            </div>

            <?php

            echo $args['after_widget'];
        }

    }

}

// ------------------------- FRONT OFFICE -------------------------

/**
 * Add modal form contact after body open tag
 * @author Savinien Maire
 */


if(form_activate() == 1) {

    function custom_content_after_body_open_tag() {

        if (!isset($_SESSION["msgSend"])) {

        ?>

        <div id="modal" class="no-visible">
            <div id="modalContent">
                <div id="modalContentHeader">
                    <h2 class="widget-title"><?php echo __('Contact us !', 'contactform'); ?><i class="em em-rocket"></i></h2>
                    <div id="close"><?php echo __('Close', 'contactform'); ?></div>
                </div>
                <div ng-app="mainApp" ng-controller="formController">
                    <form id="angularForm" name="angularForm" method="POST" action="wp-content/plugins/form-contact-angular/traitement/insert_form.php">

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
                        <h3 class="widget-title"><?php echo __('Votre demande de contact à bien été envoyé', 'contactform'); ?><i class="em em---1"></i></h3>
<!--                        <div id="close" style="top: auto; bottom: 20px">--><?php //echo __('Close', 'contactform'); ?><!--</div>-->
                    </div>
                </div>
            </div>

        <?php

        unset($_SESSION["msgSend"]);

        }

    }
    add_action('wp_footer', 'custom_content_after_body_open_tag');
}


/**
 * Add form contact js file to front office
 * @author Savinien Maire
 */


if(form_activate() == 1) {

    function form_angular_js()
    {

        wp_enqueue_script('form-contact-angular', esc_url(plugins_url('webroot/js/form-contact.js', __FILE__)), array(), '1.0', true);

    }

    add_action('wp_enqueue_scripts', 'form_angular_js');
}


/**
 * Add css file to front office
 * @author Savinien Maire
 */


if(form_activate() == 1) {

    function insert_css_head()
    {

        // Ajout du css dans le Header
        wp_register_style('form-contact-angular-css', esc_url(plugins_url('webroot/style/form-contact.css', __FILE__)));
        wp_enqueue_style('form-contact-angular-css');

    }

    add_action('wp_enqueue_scripts', 'insert_css_head');
}

function link_emojicss()
{

    echo '<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">';

}

add_action('wp_head', 'link_emojicss');


/**
 * Drop table 'wp_contact' to database when the plugin is disabled
 * @author Savinien Maire
 */

function delete_table_contact() {

    global $wpdb;

    $nom_table = $wpdb->prefix . 'contact';

    $sql = "DROP TABLE IF EXISTS $nom_table";

    $wpdb->query($sql);

}
register_deactivation_hook( __FILE__, 'delete_table_contact');


/**
 * Drop table 'wp_contact_field' to database when the plugin is disabled
 * @author Savinien Maire
 */

function delete_table_field() {

    global $wpdb;

    $nom_table = $wpdb->prefix . 'contact_field';

    $sql = "DROP TABLE IF EXISTS $nom_table";

    $wpdb->query($sql);

}
register_deactivation_hook( __FILE__, 'delete_table_field');

/**
 * Drop table 'wp_contact_form' to database when the plugin is disabled
 * @author Savinien Maire
 */

function delete_table_form() {

    global $wpdb;

    $nom_table = $wpdb->prefix . 'contact_form';

    $sql = "DROP TABLE IF EXISTS $nom_table";

    $wpdb->query($sql);

}
register_deactivation_hook( __FILE__, 'delete_table_form');