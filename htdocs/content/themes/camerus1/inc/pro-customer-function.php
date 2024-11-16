<?php
/**
 * Add Search Query product to session
 *
 * @param $user_id
 * @param string $field
 *
 * @return bool
 */
function isProCustomer($user_id = 0, $field = 'id')
{
    if (empty($user_id)) {
        $user_id = get_current_user_id();
    }

    if (!empty($user_id)) {
        $user = get_user_by($field, $user_id);
        if (!empty($user)) {
            $roles = $user->roles;
            if (!empty($roles) && is_array($roles) && in_array('procustomer', $roles)) {
                return true;
            }
        }
    }

    return false;
}

function isEventSalonSession()
{
    $slug = getEventSalonCitySlugInSession();

    if ($slug == 'event') {
        return true;
    }

    return false;
}

/**
 * @return bool
 */
function isActiveSalonSystem()
{
//    if (isProCustomer()) {
//        return false;
//    }

    return true;
}

/**
 * @param $user_id
 *
 * @return bool
 */
function sendShopManagersNotification($user_id)
{
    $user = get_user_by('id', $user_id);
    if (!empty($user)) {
        $users = get_users(['role__in' => ['manager_shop']]);
        $pro_emails = '';
        if (!empty($users) && is_array($users)) {
            foreach ($users as $_user) {
                $pro_emails .= $_user->user_email . ',';
            }

            $data = [];
            $data['email_type'] = 'procustomer-manager-validation';
            $data['user_firstname'] = $user->user_firstname;
            $data['user_lastname'] = $user->user_lastname;
            $data['user_login'] = $user->user_login;
            $data['user_email'] = $user->user_email;
            $data['user_url'] = $user->user_url;
            $data['user_id'] = $user_id;
            $to = rtrim($pro_emails, ',');
            $subject = __("Nouvelle Demande d'accès à nos tarifs événements en attente de validation", THEME_TD);

            return sendEmailType($to, $subject, $data);
        }
    }

    return false;
}