<?php
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<li class="account">
    <a href="#" title="Connexion" rel="nofollow">
        <i class="icon icon-topbar-account"></i>
    </a>
    <div data-uk-dropdown="boundary: .block-topbar__tools;boundary-align:true;pos:bottom-right">

        <div class="lwa lwa-default card card-tools__connexion"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
            <form class="lwa-form card-content" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>"
                  method="post">
                <div class="card-header">
                    <?php _e('Connectez-vous', THEME_TD) ?>
                </div><!-- .card-header -->
                <div>
                    <span class="lwa-status"></span>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tools__connexion-email"><?php _e('E-mail', THEME_TD) ?></label>
                            <input type="text" name="log" id="tools__connexion-email"
                                   placeholder="<?php _e('Saisissez votre email', THEME_TD) ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="tools__connexion-password"><?php _e('Mot de passe', THEME_TD) ?></label>
                            <input type="password" name="pwd" id="tools__connexion-password"
                                   placeholder="<?php _e('Saisissez votre mot de passe', THEME_TD) ?>"/>
                            <?php if (!empty($lwa_data['remember'])): ?>
                                <a class="lwa-links-remember"
                                   href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>"
                                   title="<?php esc_attr_e('Password Lost and Found', 'login-with-ajax') ?>"><?php esc_attr_e('Lost your password?',
                                        'login-with-ajax') ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="wp-submit"
                                   class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add" id="lwa_wp-submit"
                                   value="<?php esc_attr_e('Log In', 'login-with-ajax'); ?>" tabindex="100"/>
                            <input type="hidden" name="lwa_profile_link"
                                   value="<?php echo esc_attr($lwa_data['profile_link']); ?>"/>
                            <input type="hidden" name="login-with-ajax" value="login"/>
                            <?php if (!empty($lwa_data['redirect'])): ?>
                                <input type="hidden" name="redirect_to"
                                       value="<?php echo esc_url($lwa_data['redirect']); ?>"/>
                            <?php endif; ?>
                        </div>
                        <div class="form-group"><?php do_action('login_form'); ?></div>
                    </div><!-- .card-body -->
                    <div class="card-footer">
                        <strong class="title"><?php _e('Vous n’avez pas de compte ?', THEME_TD) ?></strong>
                        <a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>"
                           class="lwa-links-register lwa-links-modal btn btn-tt_u btn-add">
                            <span><?php esc_html_e('Register', 'login-with-ajax') ?></span>
                        </a>
                    </div><!-- .card-footer -->
                </div>
            </form>

            <?php if (!empty($lwa_data['remember']) && $lwa_data['remember'] == 1): ?>
                <form class="lwa-remember card-content" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>"
                      method="post"
                      style="display:none;">
                    <div class="card-header">
                        <?php esc_html_e("Forgotten Password", 'login-with-ajax'); ?>
                    </div><!-- .card-header -->
                    <div>
                        <span class="lwa-status"></span>
                        <div class="card-body">
                            <div class="form-group lwa-remember-email">
                                <?php $msg = __("Saisir votre E-mail", THEME_TD); ?>
                                <input type="text" name="user_login" class="lwa-user-remember"
                                       value="<?php echo esc_attr($msg); ?>"
                                       onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}"
                                       onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}"/>
                                <?php do_action('lostpassword_form'); ?>
                            </div>
                            <div class="form-group lwa-remember-buttons">
                                <input type="submit"
                                       value="<?php esc_attr_e("Réinitialiser", THEME_TD); ?>"
                                       class="lwa-button-remember btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add"/><br>
                                <a href="#"
                                   class="lwa-links-remember-cancel btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add"><?php esc_html_e("Cancel",
                                        'login-with-ajax'); ?></a>
                                <input type="hidden" name="login-with-ajax" value="remember"/>
                            </div>
                            <div class="form-group"><?php do_action('login_form'); ?></div>
                        </div><!-- .card-body -->
                    </div>
                </form>
            <?php endif; ?>

            <?php if (get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1): ?>
                <div class="lwa-register lwa-register-default lwa-modal" style="display:none;">
                    <h4 class="block-header"><?php esc_html_e('Register For This Site', 'login-with-ajax') ?></h4>
                    <p>
                        <em class="lwa-register-tip"><?php esc_html_e('A password will be e-mailed to you.', 'login-with-ajax') ?></em>
                    </p>
                    <form class="lwa-register-form" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>"
                          method="post">
                        <div>
                            <span class="lwa-status"></span>
                            <p class="lwa-username">
                                <label><?php esc_html_e('Username', 'login-with-ajax') ?><br/>
                                    <input type="text" name="user_login" id="user_login" class="form-control input" size="20"
                                           tabindex="10"/></label>
                            </p>
                            <p class="lwa-email">
                                <label><?php esc_html_e('E-mail', 'login-with-ajax') ?><br/>
                                    <input type="text" name="user_email" id="user_email" class="form-control input" size="25"
                                           tabindex="20"/></label>
                            </p>
                            <?php do_action('register_form'); ?>
                            <?php do_action('lwa_register_form'); ?>
                            <p class="submit">
                                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-bgc_1 btn-tt_u btn-c_w btn-fz_12"
                                       value="<?php esc_attr_e('Register', 'login-with-ajax'); ?>" tabindex="100"/>
                            </p>
                            <input type="hidden" name="login-with-ajax" value="register"/>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

    </div>
</li>