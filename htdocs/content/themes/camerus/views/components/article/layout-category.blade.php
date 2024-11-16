<div id="layout" class="layout container-fluid">
    <div class="layout-body inner">
        <div class="row">

            <div class="col-sm-10 col-sm-offset-1">
                <div class="uk-grid-small" data-uk-grid>
                    <?php
                    $class_block = 'uk-width-1-1@m';
                    if (!empty($category) && !empty($category->count)) {
                        $class_block = 'uk-width-1-3@m';
                    }
                    ?>

                    <div class="block block-blog__introduction {{ $class_block }}">
                        <div class="block-content">
                            <div class="block-header">
                                <figure class="aligncenter img-container">
                                    @if(!empty($category) && !empty($category->logo))
                                        {!! wp_get_attachment_image($category->logo['ID'], 'other-product-thumbnail') !!}
                                    @else
                                        <img src="{{ get_template_directory_uri() . '/dist/images/blog__introduction-lightbulb.png' }}" width="59" height="72"
                                             class="" alt="{{ $category->name }} {{ SITE_MAIN_SYS_NAME }} Mobilier"
                                             srcset="{{ get_template_directory_uri() . '/dist/images/blog__introduction-lightbulb.png' }}"/>
                                    @endif
                                </figure>
                                <strong class="title">{!! $category->name !!}</strong>
                            </div><!-- .block-header -->
                            <div class="block-body">
                                <div class="summary">
                                    <p>{!! $category->description !!}</p>
                                </div>
                            </div><!-- .block-body -->
                            <div class="block-footer"></div><!-- .block-footer -->
                        </div><!-- .block-content -->
                    </div><!-- .block-blog__introduction -->

                    @include('components.article.category-article')

                </div>
            </div><!-- .col -->

        </div>
    </div><!-- .layout-body -->
</div><!-- #layout -->