<p><?php _e('Bonjour', THEME_TD) ?>,</p>
<p><?php _e('Une nouvelle demande de devis a été effectuer et en attente de validation', THEME_TD) ?>.</p>

<h2 style="margin: 0 0 5px; color: #4e4e4e; line-height: 150%"><strong><?php _e('Client', THEME_TD) ?></strong></h2>

<p>
    <strong><?php _e('Nom', THEME_TD) ?>
        : </strong> {!! $user->user_firstname.' '.$user->user_lastname !!}</p>
<p>
    <strong><?php _e('Email', THEME_TD) ?>
        : </strong> {!! $user->user_email !!}</p>
@if(array_key_exists('company', $cart_customer->billing))
    <p>
        <strong><?php _e('Société', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['company'] !!}</p>
@endif
@if(array_key_exists('phone', $cart_customer->billing))
    <p>
        <strong><?php _e('Phone', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['phone'] !!}</p>
@endif
@if(array_key_exists('address_1', $cart_customer->billing))
    <p>
        <strong><?php _e('Adresse 1', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['address_1'] !!}</p>
@endif
@if(array_key_exists('address_2', $cart_customer->billing))
    <p>
        <strong><?php _e('Adresse 2', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['address_2'] !!}</p>
@endif
@if(array_key_exists('city', $cart_customer->billing))
    <p>
        <strong><?php _e('Ville', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['city'] !!}</p>
@endif
@if(array_key_exists('postcode', $cart_customer->billing))
    <p>
        <strong><?php _e('Code postal', THEME_TD) ?>
            : </strong> {!! $cart_customer->billing['postcode'] !!}</p>
@endif

<p style="margin: 16px 0 16px; border-bottom: 2px solid #ff560d"></p>

<h2 style="margin: 0 0 5px; color: #4e4e4e; line-height: 150%"><strong><?php _e('Produits', THEME_TD) ?></strong></h2>

@if(!empty($products) && is_array($products))
    @foreach($products as $product)
        <p>
            <strong><a href="{{ get_permalink($product->get_id()) }}">{!! $product->get_title() !!}</a> : </strong>
            <strong>Ref {!! $product->get_sku() !!} </strong> - {{ $product->get_price().'€' }} x{{ $product->quantity }}
            = <strong>{{ $product->total.'€' }}</strong></p>
    @endforeach
@endif

<p style="margin: 16px 0 16px; border-bottom: 2px solid #ff560d"></p>

<h2 style="margin: 0 0 5px; color: #4e4e4e; line-height: 150%">
    <strong><?php _e('Total', THEME_TD) ?></strong></h2>

<p>
    <strong><?php _e('Mobilier', THEME_TD) ?> : </strong> {!! $cart_subtotal.'€' !!}<br>
    <strong><?php _e('Assurance', THEME_TD) ?> : </strong> {!! $cart_total_fee.'€' !!}<br>
    <strong><?php _e('TVA', THEME_TD) ?> : </strong> {!! $cart_total_tax.'€' !!}<br>
    <strong><?php _e('Total', THEME_TD) ?> : </strong> {!! $cart_total.'€' !!}
</p>