<?php
/**
 * @since 6.9.33 https://github.com/aamplugin/advanced-access-manager/issues/392
 * @since 6.9.12 https://github.com/aamplugin/advanced-access-manager/issues/292
 * @since 6.8.0  https://github.com/aamplugin/advanced-access-manager/issues/195
 * @since 6.4.0  Allowing to define 404 for any user or role
 * @since 6.0.0  Initial implementation of the templates
 *
 * @version 6.9.33
 *
 */
?>

<?php if (defined('AAM_KEY')) { ?>
    <?php $subject = AAM_Backend_Subject::getInstance(); ?>

    <div class="aam-feature" id="404redirect-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="aam-overwrite" id="aam-404redirect-overwrite" style="display: <?php echo ($this->isOverwritten() ? 'block' : 'none'); ?>">
                    <span><i class="icon-check"></i> <?php echo __('Settings are customized', AAM_KEY); ?></span>
                    <span><a href="#" id="404redirect-reset" class="btn btn-xs btn-primary"><?php echo __('Reset to default', AAM_KEY); ?></a></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?php $type = $this->getOption('404.redirect.type', 'default'); ?>

                <div class="radio">
                    <input type="radio" name="404.redirect.type" id="404redirect-default" data-action="#default-redirect-action" value="default" <?php echo ($type === 'default' ? ' checked' : ''); ?> />
                    <label for="404redirect-default"><?php echo __('WordPress default behavior', AAM_KEY); ?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="404.redirect.type" id="404redirect-page" data-action="#page-404redirect-action" value="page_redirect" <?php echo ($type === 'page' ? ' checked' : ''); ?> />
                    <label for="404redirect-page"><?php echo AAM_Backend_View_Helper::preparePhrase('Redirected to existing page [(select from the drop-down)]', 'small'); ?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="404.redirect.type" id="404redirect-url" data-action="#url-404redirect-action" value="url_redirect" <?php echo ($type === 'url' ? ' checked' : ''); ?> />
                    <label for="404redirect-url"><?php echo AAM_Backend_View_Helper::preparePhrase('Redirected to the local URL [(enter full URL starting from http or https)]', 'small'); ?></label>
                </div>
                <?php if ($subject->isVisitor()) { ?>
                    <div class="radio">
                        <input type="radio" name="404.redirect.type" id="404-redirect-login" value="login_redirect" data-action="none" <?php echo ($type === 'login' ? ' checked' : ''); ?> />
                        <label for="404-redirect-login"><?php echo AAM_Backend_View_Helper::preparePhrase('Redirect to the login page [(after login, user will be redirected back to the restricted page)]', 'small'); ?></label>
                    </div>
                <?php } ?>
                <div class="radio">
                    <input type="radio" name="404.redirect.type" id="404redirect-callback" data-action="#callback-404redirect-action" value="trigger_callback" <?php echo ($type === 'callback' ? ' checked' : ''); ?> />
                    <label for="404redirect-callback"><?php echo sprintf(AAM_Backend_View_Helper::preparePhrase('Trigger PHP callback function [(valid %sPHP callback%s is required)]', 'small'), '<a href="https://php.net/manual/en/language.types.callable.php" target="_blank">', '</a>'); ?></label>
                </div>

                <div class="form-group 404redirect-action" id="page-404redirect-action" style="display: <?php echo ($type == 'page' ? 'block' : 'none'); ?>;">
                    <label><?php echo __('Existing Page', AAM_KEY); ?></label>
                    <?php
                        wp_dropdown_pages(array(
                            'depth' => 99,
                            'selected' => $this->getOption('404.redirect.page'),
                            'echo' => 1,
                            'name' => '404.redirect.page',
                            'id' => '404redirect-page',
                            'class' => 'form-control',
                            'show_option_none' => __('-- Select Page --', AAM_KEY)
                        ));
                        ?>
                </div>

                <div class="form-group 404redirect-action" id="url-404redirect-action" style="display: <?php echo ($type === 'url' ? 'block' : 'none'); ?>;">
                    <label><?php echo __('The URL', AAM_KEY); ?></label>
                    <input type="text" class="form-control" name="404.redirect.url" placeholder="https://" value="<?php echo stripslashes(esc_js($this->getOption('404.redirect.url'))); ?>" />
                </div>

                <div class="form-group 404-redirect-action" id="callback-404redirect-action" style="display: <?php echo ($type === 'callback' ? 'block' : 'none'); ?>;">
                    <label><?php echo __('PHP Callback Function', AAM_KEY); ?></label>
                    <input type="text" class="form-control" placeholder="Enter valid callback" name="404.redirect.callback" value="<?php echo stripslashes(esc_js($this->getOption('404.redirect.callback'))); ?>" />
                </div>
            </div>
        </div>
    </div>
<?php }