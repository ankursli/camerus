<?php
use Illuminate\Support\Facades\Input;

$old_produc_args = getSearchQueryToSession();
$s_r = request()->get('s_r');
$search_url = product_search_page_url();
$search_url = $search_url.'?old-query=1'
?>

<div id="primary" class="section container-fluid">
    <div class="section-body inner">

        <div class="row">
            <div class="col-sm-6 col-sm-offset-1">

                <div class="block block-section__breadcrumb uk">
                    <div class="block-content">
                        <?php  woocommerce_breadcrumb() ?>
                    </div><!-- .block-content -->
                </div><!-- .block-section__breadcrumb -->

            </div><!-- .col -->

            <div class="col-sm-4 prev-btn-search hide">
                <div class="block block-section__breadcrumb uk">
                    <div class="block-conten">
                        <div class="block-content text-right">
                            <a href="/" class="prev-url"><i class="icon icon-preview-arrow-left"></i> <?php _e('Retourner à la recherche', THEME_TD); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- .row -->

    </div><!-- .section-body -->
</div><!-- #primary -->