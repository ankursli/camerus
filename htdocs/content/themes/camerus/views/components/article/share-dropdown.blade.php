<?php
$post_id = get_the_ID();
$url = get_permalink($post_id);
$title = get_the_title($post_id);
?>

<div class="dropdown" data-uk-dropdown="mode:click">
    <ul class="submenu">
        <li>
            <a href="#" data-sharer="facebook" data-hashtag="{{ SITE_MAIN_SYS_NAME }}"
               data-url="{{ $url }}" title="Facebook">
                <i class="icon icon-social-facebook"></i>
            </a>
        </li>
        <li>
            <a href="#" data-sharer="twitter" data-title="{!! $title !!}" data-hashtags="camerus"
               data-url="{{ $url }}" title="Twitter">
                <i class="icon icon-social-twitter"></i>
            </a>
        </li>
        <li>
            <a href="#" data-sharer="pinterest" data-url="{{ $url }}">
                <i class="icon icon-social-pinterest"></i>
            </a>
        </li>
        <li class="hide">
            <a href="#" title="Instagram">
                <i class="icon icon-social-instagram"></i>
            </a>
        </li>
        <li>
            <a href="#" onclick="window.print()" title="<?php _e('Imprimer', THEME_TD) ?>">
                <i class="icon icon-schedule-print"></i>
            </a>
        </li>
    </ul>
</div>