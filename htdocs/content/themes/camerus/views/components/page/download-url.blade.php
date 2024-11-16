<?php
$agenad_visual_url = get_template_directory_uri() . '/dist/images/header-logo.svg';
$agenad_visual = get_field('agenda_visual_img', 'option');
if (!empty($agenad_visual)) {
    $agenad_visual_url = wp_get_attachment_image_url($agenad_visual['ID'], 'full');
}
?>

<div class="block block-download__item uk-width-1-4@l uk-width-1-3@m uk-width-1-2">
    <a class="block-content down-stat-link" href="{{  cmrsGenerateNewAgendaPdf() }}" target="_blank"
       title="{{ $box_title }} - Agenda des salons">
        <h3 class="block-header"><?php _e('Agenda des salons', THEME_TD) ?></h3><!-- .block-header -->
        <div class="block-body img-container img-middle">
            <img src="{{ $agenad_visual_url }}" width="362" height="362" alt="">
        </div><!-- .block-body -->
        <div class="block-footer">
            <strong class="title"><?php _e('Télécharger fichiers', THEME_TD) ?> {!! $media['term']->name !!}</strong>
        </div><!-- .block-footer -->
    </a><!-- .block-content -->
</div><!-- .block-download__item -->