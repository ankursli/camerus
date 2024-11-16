<?php

use Illuminate\Support\Facades\View;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

/**
 * Load Custom WC Emails
 */
define('CUSTOM_WC_EMAIL_PATH', get_template_directory().'/resources/Emails/');
Filter::add('woocommerce_email_classes', function ($emails) {
    /* class */
    require_once CUSTOM_WC_EMAIL_PATH.'class/class-wc-admin-new-order.php';
    require_once CUSTOM_WC_EMAIL_PATH.'class/class-wc-customer-new-order.php';

//    if (array_key_exists('WC_Email_New_Order', $emails)) {
//        unset($emails['WC_Email_New_Order']);
//    }

    $emails['WC_Custom_Email_New_Order'] = new WC_Custom_Email_New_Order();
    $emails['WC_Custom_Email_New_Customer_Order'] = new WC_Custom_Email_New_Customer_Order();

    return $emails;
}, 90, 1);

/**
 * Change wp_email content type : HTML
 */
//Filter::add("wp_mail_content_type", function () {
//    return "text/html";
//});

/**
 * Change wp_email from
 */
//Filter::add("wp_mail_from", function () {
//    return "hithere@myawesomesite.com";
//});

/**
 * Change wp_email from name
 */
//Filter::add("wp_mail_from_name", function () {
//    return bloginfo('name');
//});

/**
 * Update wp_mail default template
 */
//Action::add('wp_mail', function ($attribute) {
//    $headers[] = 'Content-type: text/html';
//    $attribute["headers"] = $headers;
//
//    $datas = [
//        'subject'    => $attribute["subject"],
//        'message'    => $attribute["message"],
//        'email_type' => 'default',
//    ];
//
//    ob_start();
//    echo View::make('components.email.layout-default', $datas)->render();
//    $output = ob_get_contents();
//    ob_end_clean();
//
//    $attribute["message"] = $output;
//
//    return $attribute;
//});

/**
 * wp_new_user_notification_email
 */
Filter::add('wp_new_user_notification_email', function ($wp_new_user_notification_email, $user, $blogname) {
    Filter::add("wp_mail_content_type", function () {
        return "text/html";
    });

    $user_roles = $user->get_role_caps();
    if (array_key_exists('procustomer', $user_roles)) {
        return false;
    }

    $datas = [
        'subject'    => $wp_new_user_notification_email["subject"],
        'message'    => $wp_new_user_notification_email["message"],
        'email_type' => 'default',
    ];

    ob_start();
    echo View::make('components.email.layout-default', $datas)->render();
    $output = ob_get_contents();
    ob_end_clean();

    $wp_new_user_notification_email["message"] = $output;

    return $wp_new_user_notification_email;

}, 10, 3);

/**
 * retrieve_password_message hook
 */
Filter::add('retrieve_password_message', function ($message, $key, $user_login, $user_data) {
    Filter::add("wp_mail_content_type", function () {
        return "text/html";
    });

    $datas = [
        'subject'    => __('Password Reset'),
        'message'    => $message,
        'email_type' => 'default',
    ];

    ob_start();
    echo View::make('components.email.layout-default', $datas)->render();
    $message = ob_get_contents();
    ob_end_clean();

    return $message;
}, 10, 4);

/**
 * password_change_email hook
 */
Filter::add('password_change_email', function ($pass_change_email, $user, $userdata) {
    Filter::add("wp_mail_content_type", function () {
        return "text/html";
    });

    $datas = [
        'subject'    => $pass_change_email['subject'],
        'message'    => $pass_change_email['message'],
        'email_type' => 'default',
    ];

    ob_start();
    echo View::make('components.email.layout-default', $datas)->render();
    $message = ob_get_contents();
    ob_end_clean();

    $pass_change_email['message'] = $message;

    return $pass_change_email;
}, 10, 3);

/**
 * email_change_email hook
 */
Filter::add('email_change_email', function ($email_change_email, $user, $userdata) {
    Filter::add("wp_mail_content_type", function () {
        return "text/html";
    });

    $datas = [
        'subject'    => $email_change_email['subject'],
        'message'    => $email_change_email['message'],
        'email_type' => 'default',
    ];

    ob_start();
    echo View::make('components.email.layout-default', $datas)->render();
    $message = ob_get_contents();
    ob_end_clean();

    $email_change_email['message'] = $message;

    return $email_change_email;
}, 10, 3);

/**
 * new_user_email_content hook
 */
Filter::add('new_user_email_content', function ($email_text, $new_user_email) {
    Filter::add("wp_mail_content_type", function () {
        return "text/html";
    });

    $datas = [
        'subject'    => $_POST['email'],
        'message'    => $email_text,
        'email_type' => 'default',
    ];

    ob_start();
    echo View::make('components.email.layout-default', $datas)->render();
    $message = ob_get_contents();
    ob_end_clean();

    $email_text = $message;

    return $email_text;
}, 10, 2);