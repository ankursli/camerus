<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

$load_more = request()->get('view');
?>
@if(!empty($load_more) && $load_more == 'all')
    <div id="product-ajax-load-more" data-load="0" class="block block-product__link product-ajax-load-more"></div>
@else
    @if(!empty($max_num_pages))
        @php $max_num_pages = ceil($max_num_pages) @endphp

        <div class="block block-product__pagination pagination-ajax uk-width-1-1">
            <div class="block-content">
                <div class="block-header"></div><!-- .block-header -->
                <ul class="block-body uk-pagination">
                    @for( $i=1; $i <= $max_num_pages ; $i++)
                        @if($i == $paged)
                            <li class="uk-active"><span title="Page {{ $i }}">{{ $i }}</span>
                        @else
                            <li><a href="#" class="paginate" title="Page {{ $i }}" data-page="{{ $i }}">{{ $i }}</a></li>
                        @endif
                    @endfor
                    @if($max_num_pages > 1 && !empty($current_url))
                        <li class="all">
                            <a href="<?php echo add_query_arg(['view' => 'all'], $current_url); ?>" title="<?php _e('Tout afficher', THEME_TD); ?>">
                                <span><?php _e('Tout afficher', THEME_TD); ?></span>
                            </a>
                        </li>
                    @endif
                </ul><!-- .block-body -->
                <div class="block-footer"></div><!-- .block-footer -->
            </div><!-- .block-content -->
        </div><!-- .block-product__pagination -->

    @endif
@endif