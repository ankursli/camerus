<div class="block block-download__item uk-width-1-4@l uk-width-1-3@m uk-width-1-2">
    <a class="block-content zip-category-file" target="_blank" data-slug="{{ $m->slug }}" data-id="{{ $m->term_id }}" href="#"
       title="{{ $box_title }} - {{ $m->name }}">
        <h3 class="block-header">{!! $m->name !!}</h3><!-- .block-header -->
        <div class="block-body img-container img-middle">
            {!! wp_get_attachment_image(get_term_meta( $m->term_id, 'thumbnail_id', true ), 'full', false, ['title' => $m->post_title, 'width' => 362, 'height' => 362]) !!}
        </div><!-- .block-body -->
        <div class="block-footer">
            <strong class="title"><?php _e('Télécharger fichiers', THEME_TD) ?> {!! $media['term']->name !!}</strong>
        </div><!-- .block-footer -->
    </a><!-- .block-content -->
</div><!-- .block-download__item -->