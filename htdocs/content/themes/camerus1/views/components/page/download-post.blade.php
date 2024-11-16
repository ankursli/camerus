<div class="block block-download__item uk-width-1-4@l uk-width-1-3@m uk-width-1-2">
    <a class="block-content down-stat-link" href="{{ $m->guid }}" title="{{ $box_title }} - {{ $m->post_title }}" target="_blank" download>
        <h3 class="block-header">{!! $m->post_title !!}</h3><!-- .block-header -->
        <div class="block-body img-container img-middle">
            {!! wp_get_attachment_image($m->ID, 'full', false, ['title' => $m->post_title, 'width' => 362, 'height' => 362]) !!}
        </div><!-- .block-body -->
        <div class="block-footer">
            <strong class="title"><?php _e('Télécharger fichiers', THEME_TD) ?> {!! $media['term']->name !!}</strong>
            <span class="size">{{ getAttachmentSize($m->ID) }}</span>
        </div><!-- .block-footer -->
    </a><!-- .block-content -->
</div><!-- .block-download__item -->