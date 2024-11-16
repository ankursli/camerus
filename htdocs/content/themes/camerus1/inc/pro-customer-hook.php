<?php

use Themosis\Support\Facades\Action;

Action::add('wp_login', function ($user_login, WP_User $user) {
    $user_id = get_current_user_id();
    if (empty($user_id)) {
        $user_id = $user->ID;
        if (empty($user_id)) {
            $user = get_user_by('user_login', $user_login);
            $user_id = $user->ID;
        }
    }

    $is_pro = isProCustomer($user_id);
    if ($is_pro) {
        $event_city = 'event';
        setEventSalonCitySlugToSession($event_city);
        setEventProFlagToSession($is_pro, 'event');
        wc()->cart->empty_cart();
    }
}, 10, 2);

/**
 * Approve user pro customer
 */
Action::add('wpau_approve', function ($user_id) {

    $user = get_user_by('id', $user_id);

    if (!empty($user)) {
        $user_role = $user->roles;
        if (!empty($user_role) && in_array('procustomer', $user_role)) {
            $data = [];
            $data['email_type'] = 'procustomer-validate';
            $data['user_firstname'] = $user->user_firstname;
            $data['user_lastname'] = $user->user_lastname;
            $data['user_login'] = $user->user_login;
            $activation_link = add_query_arg(array('action' => 'rp', 'key' => $user->user_activation_key, 'login' => $user->user_login),
                home_url() . '/cms/wp-login.php');
//        $data['user_activation'] = home_url().'/wp-login.php?action=rp&key='.$user->user_activation_key.'&login='.$user->user_login;
            $data['user_activation'] = $activation_link;

            $to = $user->user_email;
            $subject = __("Validation inscription prestataire de l'événementiel", THEME_TD);
            sendEmailType($to, $subject, $data);
        }
    }
});

Action::add('register_new_user', function ($user_id) {
    update_user_meta($user_id, 'wp-approve-user', true);
}, 100);

Action::add('user_register', function ($user_id) {
    $user = get_user_by('id', $user_id);

    if (!empty($user)) {
        $user_role = $user->roles;
        if (!empty($user_role) && !in_array('procustomer', $user_role)) {
            update_user_meta($user_id, 'wp-approve-user', true);
        }
    }
}, 100);
