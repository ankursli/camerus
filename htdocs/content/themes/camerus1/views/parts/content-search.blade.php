<?php
global $post_type;

$post = get_post(Loop::id());
?>
@if($post->post_type === 'product')
    <div class="block block-product__link uk-width-1-3@m uk-width-1-2@s">
        <div class="block-content">
            <a class="block-header" href="#" title="Réf. 116800">
                <span class="ref match-1">Réf. 116800 - <span class="category">Chaises</span></span>
                <figure class="img-container img-middle">
                    <img src="images/product__link-img1.jpg" width="378" height="400" class="" alt=""
                         srcset="images/product__link-img1.jpg"/>
                </figure>
            </a><!-- .block-header -->
            <div class="block-body">
                <h3 class="title">{{ $post->post_title }}</h3>
                <div class="cart uk-flex uk-flex-middle uk-flex-between">
                    <div class="price">
                        <span>Ville - </span>
                        <strong><span class="woocommerce-Price-amount amount">42,00<span
                                        class="woocommerce-Price-currencySymbol">€</span></span><span
                                    class="woocommerce-Price-amount amount">45,00<span
                                        class="woocommerce-Price-currencySymbol">€</span></span></strong>
                    </div>
                    <div class="num-spinner uk-flex">
                            <span class="btn-container uk-flex uk-flex-column">
                              <button onclick="ui.ns.increment('+',this)" type="button" class="btn"><i
                                          class="icon icon-product-arrow-up"></i></button>
                              <button onclick="ui.ns.increment('-',this)" type="button" class="btn"><i
                                          class="icon icon-product-arrow-down"></i></button>
                            </span>
                        <input type="number" name="product__characteristics-quantity" value="1">
                    </div>
                </div><!-- .cart -->
            </div><!-- .block-body -->
            <div class="block-footer hide">
                <button class="btn">
                    <span>Ajouter au panier</span>
                </button>
                <a href="#" title="Ajouter aux favoris" data-uk-tooltip>
                    <i class="icon icon-product-star-1"></i>
                </a>
            </div><!-- .block-footer -->
        </div><!-- .block-content -->
    </div>
@else
    <article id="post-{{ Loop::id() }}" {!! post_class() !!}>
        <header class="entry-header">
            <h2 class="entry-title">
                <a href="{{ esc_url(get_permalink()) }}" rel="bookmark">{{ Loop::title() }}</a>
            </h2>
            @if('post' === get_post_type())
                <div class="entry-meta">
                    {!! posted_on() !!}
                    {!! posted_by() !!}
                </div>
            @endif
        </header>
        {!! post_thumbnail() !!}
        <div class="entry-summary">
            {!! Loop::excerpt() !!}
        </div>
        <footer class="entry-footer">
            @php(entry_footer())
        </footer>
    </article><!-- #post-{{ Loop::id() }} -->
@endif