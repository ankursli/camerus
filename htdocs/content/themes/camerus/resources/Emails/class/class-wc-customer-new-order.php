<?php
/**
 * Class WC_Email_Customer_On_Hold_Order file.
 *
 * @package WooCommerce\Emails
 */

use Illuminate\Support\Facades\View;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WC_Custom_Email_New_Customer_Order', false)) :

    /**
     * Customer On-hold Order Email.
     *
     * An email sent to the customer when a new order is on-hold for.
     *
     * @class       WC_Email_Customer_On_Hold_Order
     * @version     2.6.0
     * @package     WooCommerce/Classes/Emails
     * @extends     WC_Email
     */
    class WC_Custom_Email_New_Customer_Order extends WC_Email
    {

        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->id = 'custom_customer_on_hold_order';
            $this->customer_email = true;
            $this->title = __('Nouvelle commande sur', THEME_TD) . ' ' . SITE_MAIN_SYS_NAME . ' (user)';
            if (isEventSalonSession()) {
                $this->title = __('Nouvelle demande de devis', 'woocommerce');
            }
            $this->description = __('This is an order notification sent to customers containing order details after an order is placed on-hold.',
                'woocommerce');
//            $this->template_html = 'emails/customer-on-hold-order.php';
//            $this->template_plain = 'emails/plain/customer-on-hold-order.php';
            $this->placeholders = array(
                '{order_date}' => '',
                '{order_number}' => '',
            );

            // Triggers for this email.
            add_action('woocommerce_order_status_pending_to_processing_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_pending_to_on-hold_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_on-hold_to_processing_notification', array($this, 'trigger'), 10, 2);

            add_action('woocommerce_order_status_failed_to_on-hold_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_failed_to_processing_notification', array($this, 'trigger'), 10, 2);

            add_action('woocommerce_order_status_cancelled_to_on-hold_notification', array($this, 'trigger'), 10, 2);
            add_action('woocommerce_order_status_cancelled_to_processing_notification', array($this, 'trigger'), 10, 2);

            // Call parent constructor.
            parent::__construct();
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
                return __('[' . SITE_MAIN_SYS_NAME . ']: Nouvelle demande de devis', THEME_TD);
            }
            return __('Your ' . SITE_MAIN_SYS_NAME . ' order has been received!', 'woocommerce');
        }

        public function get_subject()
        {
            $order = $this->object;
            $subject = '[' . SITE_MAIN_SYS_NAME . ']:' . __('Nouvelle commande', THEME_TD);
            if (isEventSalonSession()) {
                $subject = '[' . SITE_MAIN_SYS_NAME . ']:' . __('Demande de devis', THEME_TD);
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
            return __('Thank you for your order', 'woocommerce');
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
                $this->recipient = $this->object->get_billing_email();
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
            return View::make('shop.emails.customer-new-order', $args)->render();
        }

        /**
         * Get content plain.
         *
         * @return string
         */
        public function get_content_plain()
        {
//            return wc_get_template_html(
//                $this->template_plain,
//                array(
//                    'order'              => $this->object,
//                    'email_heading'      => $this->get_heading(),
//                    'additional_content' => $this->get_additional_content(),
//                    'sent_to_admin'      => false,
//                    'plain_text'         => true,
//                    'email'              => $this,
//                )
//            );
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
            return __('We look forward to fulfilling your order soon.', 'woocommerce');
        }
    }

endif;

//return new WC_Custom_Email_New_Customer_Order();
