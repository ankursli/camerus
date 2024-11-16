@php
    $cat = get_the_category(Loop::id());
    $category = '';
    if(!empty($cat) && is_array($cat)) {
     $category = $cat[0];
     $category->logo = get_field('cat_logo', 'category_' . $category->term_id);
     $category->f_img = get_field('cat_f_img', 'category_' . $category->term_id);
    }
@endphp

<main id="main" {!! post_class() !!}>

    {!! do_action('breadcrumb_navigation') !!}

    <div id="heading" class="layout container-fluid">
        <div class="layout-body inner">
            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <div class="uk-grid-small" data-uk-grid>

                        <div class="block block-inspirations__logo uk-width-1-1">
                            <div class="block-content">
                                <a class="block-body" href="{{ home_url() }}/blog">
                                    @if(!empty($category) && !empty($category->f_img))
                                        {!! wp_get_attachment_image($category->f_img['ID'], 'full') !!}
                                    @endif
                                </a><!-- .block-body -->
                            </div><!-- .block-content -->
                        </div><!-- .block-inspiration__logo -->

                        @include('components.article.blog-filter')

                    </div>
                </div><!-- .col -->

            </div>
        </div><!-- .layout-body -->
    </div><!-- #heading -->

    @include('components.article.layout-article')

    @include('components.page.footer-social')

</main><!-- #main -->