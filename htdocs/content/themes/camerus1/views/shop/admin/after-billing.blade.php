<h3><?php _e('Autres infos', THEME_TD) ?></h3>
<div class="address">
    <p>
        <b>Civilité: </b>{!! $order->get_meta('billing_genre') !!}<br>
        <b>N° TVA: </b>{!! $order->get_meta('_billing_eu_vat_number') !!}<br>
        <b>Facture dématérialisée: </b>{!! $order->get_meta('_billing_dematerialized_invoice') !!}<br>
        <b>Clients Professionnels: </b>{!! isProCustomer($order->get_customer_id()) ? 'OUI' : 'NON' !!}<br>
        <b>Adresse mail comptabilité: </b>{!! $order->get_meta('billing_accounting_email') !!}<br>
        <?php if (!empty($reed_token = $order->get_meta('reed_token'))) : ?>
        <span style="overflow: hidden">
            <b>Reed Token/Hash: </b><a href="<?php echo home_url() . '/reed/' . $reed_token; ?>" target="_blank">Lien Token Reed</a><br>
        </span>
        <?php endif; ?>
    </p>
</div>