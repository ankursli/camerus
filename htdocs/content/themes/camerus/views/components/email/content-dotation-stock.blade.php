<p><?php _e('Etat de stock de dotation ', THEME_TD) ?></p>

<p>
    <?php _e('Informations sur la dotation', THEME_TD); ?> :</p>
<p>

<p>
    <b style="color: #272727;"><?php _e('Nom de la dotation', THEME_TD) ?>
        : </b> {!! $product->get_title() !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Référence de la dotation', THEME_TD) ?>
        : </b> {!! $dotation_ref !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Type ou famille de la dotation', THEME_TD) ?>
        : </b> {!! $dotation_type !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Nom du salon', THEME_TD) ?>
        : </b> {!! $salon->post_title !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Référence du salon', THEME_TD) ?>
        : </b> {!! $salon_ref !!}</p>
<p>
    <b style="color: #272727;"><?php _e('Stock disponible', THEME_TD) ?>
        : </b> <strong style="color: red">{!! $stock !!}</strong></p>