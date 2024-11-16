<div class="col-lg-8 col-sm-7">

    <div class="uk-grid-small" data-uk-grid>
        <!-- blocks -->

        <div class="block block-product__filter uk-width-1-1">
            <div class="block-content">
                <form class="block-body" method="GET" action="{{ $current_url }}">
                    <div class="form-group">
                        <label for="product__filter-order"><?php _e('Trier par', THEME_TD) ?> </label>
                        <select name="orderby" id="download__filter-order"
                                class="select" onchange="this.form.submit()">
                            <option value="name"><?php _e('Nom', THEME_TD) ?></option>
                            <option value="date"><?php _e('Date', THEME_TD) ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product__filter-filter"><?php _e('Filtrer par', THEME_TD) ?> </label>
                        <select name="order" id="download__filter-filter"
                                class="select" onchange="this.form.submit()">
                            <option value="ASC"><?php _e('Croissant', THEME_TD) ?></option>
                            <option value="DESC"><?php _e('Décroissant', THEME_TD) ?></option>
                        </select>
                    </div>
                </form><!-- .block-body -->
            </div><!-- .block-content -->
        </div><!-- .block-product__filter -->

        @if(is_page_template('download-template'))
            <div class="block block-download__title uk-width-1-1">
                <h2 class="block-body"
                    style="text-align: center; font-size: 20px;"><?php _e('Bienvenue dans notre rubrique téléchargements', THEME_TD); ?></h2>
                <!-- .block-body -->
            </div><!-- .block-download__item -->
        @else
            @if(!empty($medias) && is_array($medias))
                @foreach($medias as $key => $media)
                    <?php $box_title = strtoupper($media['term']->name);?>

                    <div class="block block-download__title uk-width-1-1">
                        <a class="block-content" href="{{ get_term_link($media['term']->term_id) }}">
                            <h2 class="block-body" style="text-transform: uppercase;">{!! $box_title  !!}</h2>
                            <!-- .block-body -->
                        </a><!-- .block-content -->
                    </div><!-- .block-download__item -->

                    @if(!empty($media['medias_list']) && is_array($media['medias_list']))
                        @foreach($media['medias_list'] as $m)

                            @if($media['type'] === 'url')

                                @include('components.page.download-url')

                            @elseif($media['type'] === 'post')

                                @include('components.page.download-post')

                            @elseif($media['type'] === 'zip-file')

                                @include('components.page.download-zip-file')

                            @else
                                @if($m->slug !== 'uncategorized')

                                    @include('components.page.download-default')

                                @endif
                            @endif

                        @endforeach
                    @else
                        <div class="block block-download__item uk-width-1-2@l uk-width-1-2@m uk-width-1-2">
                            <p class="text-center"><?php _e('Pas de fichier dans ce catégorie', THEME_TD) ?></p>
                        </div>
                @endif

            @endforeach
        @endif
    @endif

    <!-- end: blocks -->
    </div>

</div><!-- .col -->