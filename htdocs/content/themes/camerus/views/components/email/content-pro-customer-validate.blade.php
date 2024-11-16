<p><?php _e('Bonjour', THEME_TD) ?>,</p>
<p><?php _e("Votre inscription sur le site Camerus en tant que prestataire de l'événementiel a été validée avec succès",
        THEME_TD) ?></p>
<p><?php _e('Vous pouvez maintenant vous connecter sur le site pour commander votre mobilier.',
        THEME_TD) ?></p>

<p>
    <b style="color: #272727;"><?php _e('Nom', THEME_TD) ?>
        : </b> {!! $user_firstname.' '.$user_lastname !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Login', THEME_TD) ?>
        : </b> {!! $user_login !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Lien pour votre mot de passe', THEME_TD) ?>
        : </b> {!! $user_activation !!}</p>