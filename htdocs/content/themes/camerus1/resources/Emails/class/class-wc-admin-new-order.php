<?php
/**
 * Class WC_Email_New_Order file
 *
 * @package WooCommerce\Emails
 */

use Illuminate\Support\Facades\View;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WC_Custom_Email_New_Order')) :

    /**
     * Custom New Order Email.
     *
     * An email sent to the admin when a new order is received/paid for.
     *
     * @class       WC_Custom_Email_New_Order
     * @version     2.0.0
     * @extends     WC_Email
     */
    class WC_Custom_Email_New_Order extends WC_Email
    {

        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->id = 'custom_new_order';
            $this->title = __('Nouvelle commande sur ' . SITE_MAIN_SYS_NAME . ' (admin)', THEME_TD);
            if (isEventSalonSession()) {
                $this->title = __('Demande de devis', THEME_TD);
            }
            $this->description = __('Custom New order emails are sent to chosen recipient(s) when a new order is received.', 'woocommerce');
//            $this->template_html = 'emails/custom-admin-new-order.php';
//            $this->template_plain = 'emails/plain/custom-admin-new-order.php';
            $this->placeholders = array(
                '{order_date}' => '',
                '{order_number}' => '',
            );

            // Triggers for this email.
            add_action('woocommerce_order_status_pending_to_processing_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_pending_to_completed_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_pending_to_on-hold_notification', array($this, 'trigger'), 10, 2);

            add_action('woocommerce_order_status_failed_to_processing_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_failed_to_completed_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_failed_to_on-hold_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_cancelled_to_processing_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_cancelled_to_completed_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_cancelled_to_on-hold_notification', array($this, 'trigger'), 10, 2);

            // Call parent constructor.
            parent::__construct();

            // Other settings.
            $this->recipient = $this->get_option('recipient', get_option('admin_email'));
        }

        /**
         * Get email subject.
         *
         * @return string
         * @since  3.1.0
         */
        public function get_default_subject()
        {
            if (isEventSalonSession()) {
                return __('[' . SITE_MAIN_SYS_NAME . ']: Demande de devis', THEME_TD);
            }
            return __('[' . SITE_MAIN_SYS_NAME . ']: Nouvelle commande (admin)', THEME_TD);
        }

        public function get_subject()
        {
            $order = $this->object;
            $subject = __('[' . SITE_MAIN_SYS_NAME . ']: Nouvelle commande (admin)', THEME_TD);
            if (isEventSalonSession()) {
                $subject = __('[' . SITE_MAIN_SYS_NAME . ']: Demande de devis', THEME_TD);
            }

            $salon_name = $order->get_meta('event_type');
            if (!empty($salon = $this->get_salon_order($order))) {
                $salon_name = $salon->post_title;
            }

            $company_name = $order->get_billing_company();

            $subject = $subject . ' / ' . $salon_name . ' - ' . $company_name;

            return $subject;
        }

        /**
         * Get email heading.
         *
         * @return string
         * @since  3.1.0
         */
        public function get_default_heading()
        {
            return __('[' . SITE_MAIN_SYS_NAME . ']: Nouvelle commande (admin)', THEME_TD);
        }

        /**
         * Trigger the sending of this email.
         *
         * @param int $order_id The order ID.
         * @param WC_Order|false $order Order object.
         */
        public function trigger($order_id, $order = false)
        {
            $this->setup_locale();

            if ($order_id && !is_a($order, 'WC_Order')) {
                $order = wc_get_order($order_id);
            }

            if (is_a($order, 'WC_Order')) {
                $this->object = $order;
                $this->placeholders['{order_date}'] = wc_format_datetime($this->object->get_date_created());
                $this->placeholders['{order_number}'] = $this->object->get_order_number();
            }

            if ($this->is_enabled() && $this->get_recipient()) {
                $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
            }

            $this->restore_locale();
        }

        /**
         * Get Salon
         */
        public function get_salon_order($order)
        {
            $salon = getEventSalonObjectInSession();
            if (empty($salon) && !isset($_POST['_event-name'])) {
                $salon = [];
                $salon['post_title'] = $order->get_meta('nom_evenement');
                $salon['salon_start_date'] = $order->get_meta('date_evenement');
                $salon['salon_end_date'] = $order->get_meta('date_fin_evenement');
                $salon['salon_place'] = $order->get_meta('lieu_evenement');
                $salon['salon_address'] = '';
                $salon['salon_ville_name'] = $order->get_meta('ville_evenement');
                $salon = (object)$salon;
            }

            return $salon;
        }

        /**
         * Get content html.
         *
         * @return string
         */
        public function get_content_html()
        {
            $args = array(
                'order' => $this->object,
                'email_heading' => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin' => true,
                'plain_text' => false,
                'email' => $this,
                'salon' => $this->get_salon_order($this->object),
            );
            return View::make('shop.emails.admin-new-order', $args)->render();
        }

        /**
         * Get content plain.
         *
         * @return string
         */
        public function get_content_plain()
        {
//            $args = array(
//                'order'              => $this->object,
//                'email_heading'      => $this->get_heading(),
//                'additional_content' => $this->get_additional_content(),
//                'sent_to_admin'      => true,
//                'plain_text'         => false,
//                'email'              => $this,
//                'salon'              => $this->get_salon_order(),
//            );
//            return View::make('shop.emails.admin-new-order', $args)->render();
            return;
        }

        /**
         * Default content to show below main email content.
         *
         * @return string
         * @since 3.7.0
         */
        public function get_default_additional_content()
        {
            return __('Congratulations on the sale.', 'woocommerce');
        }

        /**
         * Initialise settings form fields.
         */
        public function init_form_fields()
        {
            /* translators: %s: list of placeholders */
            $placeholder_text = sprintf(__('Available placeholders: %s', 'woocommerce'),
                '<code>' . implode('</code>, <code>', array_keys($this->placeholders)) . '</code>');
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable this email notification', 'woocommerce'),
                    'default' => 'yes',
                ),
                'recipient' => array(
                    'title' => __('Recipient(s)', 'woocommerce'),
                    'type' => 'text',
                    /* translators: %s: WP admin email */
                    'description' => sprintf(__('Enter recipients (comma separated) for this email. Defaults to %s.', 'woocommerce'),
                        '<code>' . esc_attr(get_option('admin_email')) . '</code>'),
                    'placeholder' => '',
                    'default' => '',
                    'desc_tip' => true,
                ),
                'subject' => array(
                    'title' => __('Subject', 'woocommerce'),
                    'type' => 'text',
                    'desc_tip' => true,
                    'description' => $placeholder_text,
                    'placeholder' => $this->get_default_subject(),
                    'default' => '',
                ),
                'heading' => array(
                    'title' => __('Email heading', 'woocommerce'),
                    'type' => 'text',
                    'desc_tip' => true,
                    'description' => $placeholder_text,
                    'placeholder' => $this->get_default_heading(),
                    'default' => '',
                ),
                'additional_content' => array(
                    'title' => __('Additional content', 'woocommerce'),
                    'description' => __('Text to appear below the main email content.', 'woocommerce') . ' ' . $placeholder_text,
                    'css' => 'width:400px; height: 75px;',
                    'placeholder' => __('N/A', 'woocommerce'),
                    'type' => 'textarea',
                    'default' => $this->get_default_additional_content(),
                    'desc_tip' => true,
                ),
                'email_type' => array(
                    'title' => __('Email type', 'woocommerce'),
                    'type' => 'select',
                    'description' => __('Choose which format of email to send.', 'woocommerce'),
                    'default' => 'html',
                    'class' => 'email_type wc-enhanced-select',
                    'options' => $this->get_email_type_options(),
                    'desc_tip' => true,
                ),
            );
        }
    }

endif;

//return new WC_Custom_Email_New_Order();
