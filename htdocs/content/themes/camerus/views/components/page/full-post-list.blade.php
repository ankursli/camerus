<div id="layout" class="layout container-fluid">
    <div class="layout-body inner">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="uk-grid-small uk-child-width-expand" data-uk-grid>

                    <!-- blocks -->

                @if(!empty($f_posts && is_array($f_posts)))
                    @foreach($f_posts as $post)
                        @include('components.article.block-article', ['post' => $post])
                    @endforeach
                @endif

                <!-- end: blocks -->

                </div>
            </div><!-- .col -->
        </div>
    </div><!-- .layout-body -->
</div><!-- #layout -->