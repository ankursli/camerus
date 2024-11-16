<?php
$nonce = encrypt(date('Y-m-d-H', time()));
$redirect_url = home_url('dotations?reload='.$nonce);

$reed_info = getReedDataInfo();
if (!empty($reed_info)) {
    $salon_ref = $reed_info->Salon;
    $salon = getSalonByRef($salon_ref);
    $salon_slug = '';
    if (!empty($salon)) {
        $salon_slug = $salon->post_name;
        $redirect_url .= '&'.SLUG_EVENT_SALON_QUERY.'='.$salon_slug;
    }
}
?>
<div id="modal-reed-change-user" class="no-close" data-uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <div class="block block-event__modal">
            <form class="block-content" action="#">
                <div class="block-header"><?php _e('Changement de compte', THEME_TD) ?></div><!-- .block-header -->
                <div class="block-body">
                    <div class="summary">
                        <p><?php echo get_field('app_popup_reed_text', 'option') ?></p>
                    </div>
                </div><!-- .block-body -->
                <div class="block-footer">
                    <a href="<?php echo wp_logout_url($redirect_url); ?>" class="btn btn-1 btn-w_a">
                        <span><?php _e('Se déconnecter', THEME_TD) ?></span>
                    </a>
                    <button onclick="UIkit.modal('#modal-reed-change-user').hide()" class="btn btn-2 btn-w_a uk-modal-close">
                        <span><?php _e('Continuer', THEME_TD) ?></span>
                    </button>
                </div><!-- .block-footer -->
            </form><!-- .block-content -->
        </div><!-- .block-event__modal -->
    </div>
</div><!-- #modal-event -->