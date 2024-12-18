<?php
namespace MFSA\Controller;

use MFSA\View\Signature_Widget_View;
use Elementor\Plugin;

class MFSA_Addon_Controller {

    public function __construct() {
        // Register widgets on Elementor initialization
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        
        // Enqueue styles
        add_action('wp_enqueue_scripts', [$this, 'mfsa_enqueue_styles']);
        
        // Add custom fields to MetForm entries
        add_filter('metform_filter_before_store_form_data', [$this, 'add_custom_fields_to_form_data'], 10, 2);
        
        // Add custom fields to email notifications
        add_filter('wp_mail', [$this, 'append_custom_fields_to_email'], 10, 1);

        // Register meta box to display custom fields in entry edit screen
        add_action('add_meta_boxes', [$this, 'add_pro_fields_meta_box']);
    }

    public function register_widgets() {
        Plugin::instance()->widgets_manager->register_widget_type(new Signature_Widget_View());
    }

    public function mfsa_enqueue_styles() {
        wp_enqueue_style(
            'metform-addon-style',
            plugin_dir_url(__DIR__) . '../assets/style.css',
            array(), // Dependencies
            '1.0.0' // Version number
        );
        wp_enqueue_script('jquery');
        wp_enqueue_script('metform-signaturepad-script', plugin_dir_url(__DIR__) . '../assets/signaturepadmin.js', array('jquery'), '4.1.7', true);
        wp_enqueue_script('metform-signature-script', plugin_dir_url(__DIR__) . '../assets/signature.js', array('jquery'), '1.0.0', true);
    }

    public function add_custom_fields_to_form_data($data, $entry_id) {
        // Nonce verification for form submission
        if (!isset($_POST['metform_signature_nonce']) || !wp_verify_nonce($_POST['metform_signature_nonce'], 'metform_signature_action')) {
            return $data; // Exit if nonce verification fails
        }

        // Sanitize and unslash the input data before adding to form data
        $signature_data = isset($_POST['mf-signature-data']) ? wp_unslash($_POST['mf-signature-data']) : null;
        if ($signature_data) {
            // You might need to use a specific sanitization depending on the signature data type
            $data['mf-signature-data'] = sanitize_text_field($signature_data);
        }

        return $data;
    }

    public function append_custom_fields_to_email($email) {
        // Nonce verification for email processing
        if (!isset($_POST['metform_signature_nonce']) || !wp_verify_nonce($_POST['metform_signature_nonce'], 'metform_signature_action')) {
            return $email; // Exit if nonce verification fails
        }

        // Sanitize the signature data and process it for the email
        $signature_data = isset($_POST['mf-signature-data']) ? wp_unslash($_POST['mf-signature-data']) : null;
        $signature_row = '';

        if ($signature_data && !strpos($email['message'], 'Signature')) {
            $signature_row = "<tr bgcolor='#EAF2FA'><td colspan='2'><strong>Signature</strong></td></tr>
                              <tr bgcolor='#FFFFFF'><td width='20'>&nbsp;</td><td>
                              <img src='" . esc_url($signature_data) . "' alt='Signature' style='width:200px; height:auto;'></td></tr>";
        }
        $email['message'] .= $signature_row;
        return $email;
    }

    // Add meta box for displaying Pro fields
    public function add_pro_fields_meta_box() {
        add_meta_box(
            'mfsa_pro_fields_meta_box',             // Unique ID for the meta box
            __('MetForm Pro Fields', 'metform-digital-signature-addon'),  // Meta box title
            [$this, 'render_pro_fields_meta_box'],   // Callback to render the meta box
            'metform-entry',                         // Screen where it will appear
            'normal',                                // Context where the box will appear
            'default'                                // Priority
        );
    }

    // Render the meta box content
    public function render_pro_fields_meta_box($post) {
        $form_data = get_post_meta($post->ID, 'metform_entries__form_data', true);
        $mf_signature = isset($form_data['mf-signature-data']) ? sanitize_text_field($form_data['mf-signature-data']) : null;

        // Display the fields in the meta box
        if ($mf_signature) {
            echo '<tr class="mf-data-label"><td colspan="2"><strong>' . __('Signature:', 'metform-digital-signature-addon') . '</strong></td></tr>';
            echo '<tr class="mf-data-value"><td class="mf-value-space">&nbsp;</td><td><img src="' . esc_attr($mf_signature) . '" alt="Signature" style="max-width:500px;" /></td></tr>';
        }

        echo '</table>';
    }
}

// Register the controller
new MFSA_Addon_Controller();
