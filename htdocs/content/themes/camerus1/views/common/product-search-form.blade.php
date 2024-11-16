<?php
use App\Hooks\Product;
use Illuminate\Support\Facades\Input;

$categories = Product::getCategories();
$colors = Product::getColors();
$uses = Product::getTags();
$materials = Product::getMaterials();
$search_url = product_search_page_url();

$inputs = getSearchQueryToSession();
$input_category = array_key_exists('category', $inputs) ? $inputs['category'] : false;
$input_pa_color = array_key_exists('pa_color', $inputs) ? $inputs['pa_color'] : false;
$input_product_material = array_key_exists('product_material', $inputs) ? $inputs['product_material'] : false;
$input_product_tag = array_key_exists('product_tag', $inputs) ? $inputs['product_tag'] : false;
$input_s = array_key_exists('s', $inputs) ? $inputs['s'] : false;
$input_paged = 1;
$input_orderby = array_key_exists('orderby', $inputs) ? $inputs['orderby'] : 'date';
$view_all = request()->get('view');
$view_all = request()->get('view');
$load_more = false;
if (!empty($view_all) && $view_all == 'all') {
    $load_more = true;
}
$salon = getEventSalonObjectInSession();
$hidden_cat = [];
if (!empty($salon)) {
    $hidden_cat = get_field('salon_hide_cat', $salon->ID);
}
?>

<form id="custom-product-search-form" method="POST" action="{{ $search_url }}">
    @csrf
    <input type="hidden" name="paged" class="custom-product-search-form-paged" value="{{ $input_paged }}">
    <?php if($load_more && !is_shop() && !is_product_category() && !is_product()) : ?>
    <input type="hidden" id="page-search-load-more" name="page-search-load-more" data-load="0" value="1"/>
    <?php endif; ?>
    <input type="hidden" name="orderby" class="custom-product-search-form-orderby" value="{{ $input_orderby }}">
    <input type="hidden" name="security"
           value="<?php echo wp_create_nonce('custom-cmrs-form' . date('Y-m-d-H', time())) ?>">
    <input type="hidden" name="event_type" value="">
    <div class="card-body">
        <div class="critaria-content">
            <div>
                <div class="title"><?php _e('Catégories', THEME_TD) ?></div>

                @if(!empty($categories) && is_array($categories))
                    @foreach($categories as $cat)

                        @if(!empty($hidden_cat))
                            @if(!in_array($cat->term_id,$hidden_cat))
                                <label class="container-input">
                                    <input name="category[]" type="checkbox" value="{{ $cat->slug }}"
                                           @if(!empty($input_category) && in_array($cat->slug, $input_category)) checked @endif>
                                    <span class="checkmark">&nbsp;</span> {!! $cat->name !!}
                                </label>
                            @endif
                        @else
                            <label class="container-input">
                                <input name="category[]" type="checkbox" value="{{ $cat->slug }}"
                                       @if(!empty($input_category) && in_array($cat->slug, $input_category)) checked @endif>
                                <span class="checkmark">&nbsp;</span> {!! $cat->name !!}
                            </label>
                        @endif

                    @endforeach
                @endif

            </div>

            <div>
                <div class="title"><?php _e('Couleurs', THEME_TD) ?></div>

                @if(!empty($colors) && is_array($colors))
                    @foreach($colors as $color)
                        @if(is_object($color))
                            <?php
                            $style = 'background-color: transparent';
                            $color_picker = get_field('pa_color_picker', 'pa_color_' . $color->term_id);
                            $color_icon = get_field('tag_icon', 'pa_color_' . $color->term_id);
                            if ($color_icon) {
                                $style = "background-image: url('" . $color_icon['sizes']['picto-color'] . "')";
                            } else {
                                $style = "background-color: " . $color_picker;
                            }
                            ?>

                            <label class="container-input coloris">
                                <input name="pa_color[]" type="checkbox" value="{{ $color->slug }}"
                                       @if(!empty($input_pa_color) && in_array($color->slug, $input_pa_color)) checked @endif>
                                <span class="checkmark" style="{{ $style }};" title="{{ $color->name }}"
                                      data-uk-tooltip>&nbsp;</span>
                            </label>

                        @endif
                    @endforeach
                @endif

            </div>

            <div>
                <div class="title"><?php _e('Matières', THEME_TD) ?></div>

                @if(!empty($materials) && is_array($materials))
                    @foreach($materials as $material)
                        <?php
                        $style = 'transparent';
                        $color_icon = get_field('tag_icon', 'product_material_' . $material->term_id);
                        if ($color_icon) {
                            $style = "url('" . $color_icon['sizes']['picto-color'] . "')";
                        }
                        ?>

                        <label class="container-input coloris mat">
                            <input name="product_material[]" type="checkbox" value="{{ $material->slug }}"
                                   @if(!empty($input_product_material) && in_array($material->slug, $input_product_material)) checked @endif>
                            <span class="checkmark" style="background-image: {{ $style }};"
                                  title="{{ $material->name }}"
                                  data-uk-tooltip>&nbsp;</span> {!! $material->name !!}
                        </label>

                    @endforeach
                @endif

                <hr>

                <div class="title"><?php _e('Usages', THEME_TD) ?></div>

                @if(!empty($uses) && is_array($uses))
                    @foreach($uses as $use)
                        <?php
                        $style = 'transparent';
                        $color_icon = get_field('tag_icon', 'product_tag_' . $use->term_id);
                        if ($color_icon) {
                            $style = "url('" . $color_icon['sizes']['picto-color'] . "')";
                        }
                        ?>

                        <label class="container-input coloris">
                            <input name="product_tag[]" type="checkbox" value="{{ $use->slug }}"
                                   @if(!empty($input_product_tag) && in_array($use->slug, $input_product_tag)) checked @endif>
                            <span class="checkmark" style="background-image: {{ $style }};" title="{{ $use->name }}"
                                  data-uk-tooltip>&nbsp;</span>
                        </label>

                    @endforeach
                @endif

            </div>
        </div>
        <div class="form-group">
            <label for="tools__search-keyworkds"></label>
            <input type="text" id="tools__search-keyworkds" name="s"
                   placeholder="<?php _e('Recherche par nom ou référence', THEME_TD) ?>"
                   @if(!empty($input_s)) value="{{ $input_s }}" @endif>
            <button type="submit" class="btn btn-c_w btn-bdc_1 btn-bgc_1 btn-tt_u btn-add header-menu-btn-search" disabled>
                <span data-btn-text="<?php _e('Rechercher', THEME_TD); ?>"><?php _e('Chargement', THEME_TD); ?></span>
            </button>
        </div>
    </div><!-- .card-body -->
</form><!-- .card-content -->