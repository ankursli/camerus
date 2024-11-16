<p><?php _e('Bonjour', THEME_TD) ?>,</p>
<p><?php _e('Une nouvelle inscription a été effectuer et en attente de validation',
        THEME_TD) ?></p>
<p><?php _e('Veuillez vous connecter sur votre compte de Gestionnaire de boutique pour approuver cet utilisateur',
        THEME_TD) ?></p>
<p>
    <strong><?php _e('Nom', THEME_TD) ?> : </strong> {!! $user_firstname.' '.$user_lastname !!}<br>
    <strong><?php _e('Email', THEME_TD) ?> : </strong> {!! $user_email !!}<br>
    <strong><?php _e('Login', THEME_TD) ?> : </strong> {!! $user_login !!}<br>
    <strong><?php _e('Lien', THEME_TD) ?> : </strong> {!! home_url().'/cms/wp-admin/user-edit.php' !!}</p>