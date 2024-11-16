@php
    $cat = get_queried_object();
    if(!empty($cat)) {
         $category = $cat;
         $category->logo = get_field('cat_logo', 'category_' . $category->term_id);
         $category->f_img = get_field('cat_f_img', 'category_' . $category->term_id);
    } elseif(is_int($cat)) {
        $category = get_term($cat, 'category');
        $category->logo = get_field('cat_logo', 'category_' . $cat);
        $category->f_img = get_field('cat_f_img', 'category_' . $cat);
    }
    $tax_args = [
       'taxonomy'   => 'category',
       'hide_empty' => false,
       'exclude'    => [1]
    ];
    $taxonomies = get_terms($tax_args)
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
                                <a class="block-body" href="{{ get_permalink(ID_LIST_POST)  }}">
                                    @if(!empty($category) && !empty($category->f_img))
                                        {!! wp_get_attachment_image($category->f_img['ID'], 'full') !!}
                                    @else
                                        <img src="{{ get_template_directory_uri() . '/dist/images/inspirations__logo-Inspirations.by.camerus.png' }}" width="406"
                                             height="207" class="" alt="Inspirations by {{ SITE_MAIN_SYS_NAME }}"
                                             srcset="{{ get_template_directory_uri() . '/dist/images/inspirations__logo-Inspirations.by.camerus.png' }}"/>
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

    @include('components.article.layout-category')

    @include('common.pagination')

    @include('components.article.category-banner')

    @include('components.page.footer-social')

</main><!-- #main -->