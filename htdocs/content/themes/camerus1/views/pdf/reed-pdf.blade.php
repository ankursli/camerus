<style type="text/css">
    #pdf {
        max-width: 768px;
        margin-left: auto;
        margin-right: auto;
        font-family: arial;
    }

    table {
        font-family: arial;
        font-size: 12px;
    }

    table thead * {
        white-space: nowrap;
    }
</style>

<div id="pdf">

    <table>
        <tr>
            <td>
                <img src="{{ get_template_directory_uri(). '/dist/images/header-logo.svg' }}" width="200" height="64" class="" alt=""
                     srcset="{{ get_template_directory_uri(). '/dist/images/header-logo.svg' }}">
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
    </table>
    <table>
        <tbody>
        <tr>
            <td><strong><?php _e('Devis', THEME_TD); ?> :</strong></td>
        </tr>
        <tr>
            <td><?php _e('Numéro', THEME_TD); ?>:&nbsp;{{ $order->get_id() }}</td>
        </tr>
        <tr>
            <td><?php _e('Date', THEME_TD); ?> : {{ $order->get_date_created() }}</td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <td><strong><?php _e('SOCIÉTÉ;', THEME_TD); ?> :</strong></td>
        </tr>
        <tr>
            <td><?php _e('Société', THEME_TD); ?> : {{ $order->get_billing_company() }}</td>
        </tr>
        <tr>
            <td><?php _e('TVA', THEME_TD); ?> : {{ get_post_meta($order->get_id(), '_billing_eu_vat_number', true) }}</td>
        </tr>
        <tr>
            <td><?php _e('Contact', THEME_TD); ?>
                : {{ get_post_meta($order->get_id(), 'billing_genre', true) }} {!! $order->get_billing_first_name() !!} {!! $order->get_billing_last_name() !!}</td>
        </tr>
        <tr>
            <td><?php _e('Tél', THEME_TD); ?> : {{ $order->get_billing_phone() }}</td>
        </tr>
        <tr>
            <td><?php _e('Mail', THEME_TD); ?> : {{ $order->get_billing_email() }}</td>
        </tr>
        <tr>
            <td><?php _e('Adresse', THEME_TD); ?> : {{ $order->get_billing_address_1() }}
                , {{ $order->get_billing_postcode() }} {{ $order->get_billing_city() }} {{ $order->get_billing_country() }}</td>
        </tr>
        <tr>
            <td><?php _e('Adresse secondaire', THEME_TD); ?> : {{ $order->get_billing_address_2() }}</td>
        </tr>
        <tr>
            <td><?php _e('Déjà client', THEME_TD); ?> : <?php _e('Oui', THEME_TD); ?></td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <td><?php _e('Zone', THEME_TD); ?> : {{ $order->get_billing_city() }}</td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <td><?php _e('Commentaires', THEME_TD); ?> : </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <td><strong><?php _e('SALON', THEME_TD); ?> :</strong></td>
        </tr>
        <tr>
            <td><?php _e('Salon', THEME_TD); ?> : {{ $salon->post_title }}</td>
        </tr>
        <tr>
            <td><?php _e('Début', THEME_TD); ?> : {{ date('d/m/Y', strtotime($salon->salon_start_date)) }}</td>
        </tr>
        <tr>
            <td><?php _e('Fin', THEME_TD); ?> : {{ date('d/m/Y', strtotime($salon->salon_end_date)) }}</td>
        </tr>
        <tr>
            <td><?php _e('Livraison', THEME_TD); ?> : {{ $salon->salon_place }} {{ $salon->salon_address }} HALL 1, {{ $salon->salon_ville_name }}, FRANCE</td>
        </tr>
        <tr>
            <td><?php _e('N° de hall', THEME_TD); ?> / <?php _e('Niveau', THEME_TD); ?> : {{ get_post_meta($order->get_id(), 'numero_de_stand', true) }}</td>
        </tr>
        <tr>
            <td><?php _e('Nom du hall', THEME_TD); ?> : {{ get_post_meta($order->get_id(), 'hall_stand', true) }}</td>
        </tr>
        <tr>
            <td><?php _e('Allée', THEME_TD); ?> / N&deg; : {{ get_post_meta($order->get_id(), 'allee_stand', true) }}</td>
        </tr>
        <tr>
            <td><?php _e('Nom du Stand', THEME_TD); ?> : {{ get_post_meta($order->get_id(), 'nom_stand', true) }}</td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table style="text-align: left" cellspacing="0" cellpadding="0">
        <thead>
        <tr style="color:white">
            <th bgcolor="#454c57" height="20" class="pic">&nbsp;<?php _e('Photo', THEME_TD); ?></th>
            <td bgcolor="#454c57" width="20"></td>
            <th bgcolor="#454c57" class="desc"><?php _e('Description du produit', THEME_TD); ?></th>
            <td bgcolor="#454c57" width="20"></td>
            <th bgcolor="#454c57" class="ref"><?php _e('Réf', THEME_TD); ?></th>
            <td bgcolor="#454c57" width="20"></td>
            <th bgcolor="#454c57" class="pu"><?php _e('Prix Unitaire', THEME_TD); ?></th>
            <td bgcolor="#454c57" width="20"></td>
            <th bgcolor="#454c57" class="qty"><?php _e('Quantité', THEME_TD); ?></th>
            <td bgcolor="#454c57" width="20"></td>
            <th bgcolor="#454c57" class="price"><?php _e('Prix HT', THEME_TD); ?>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="pic">
                {!! get_the_post_thumbnail($dotation->get_id(), 'thumbnail', ['width' => '120']) !!}
            </td>
            <td></td>
            <td class="desc" style="font-weight: bold">{!! $dotation->get_name() !!}</td>
            <td></td>
            <td class="ref">{{ $dotation->get_sku() }}</td>
            <td></td>
            <td class="pu"><?php _e('INCLUS DANS VOTRE DOTATION', THEME_TD); ?></td>
            <td></td>
            <td class="qty" align="center">1</td>
            <td></td>
            <td class="price" align="right">0,00 &euro;</td>
        </tr>
        <tr>
            <td colspan="11" height="1" bgcolor="bcbcbc"></td>
        </tr>
        </tbody>
    </table>
    <table align="center" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td height="10"></td>
        </tr>
        </tbody>
    </table>
    <table align="center" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td height="25"><?php _e('Assurance', THEME_TD); ?></td>
            <td class="width:20"></td>
            <td>0,00 &euro;</td>
        </tr>
        <tr>
            <td colspan="3" height="1" bgcolor="bcbcbc"></td>
        </tr>
        <tr>
            <td height="25"><?php _e('Sous Total HT', THEME_TD); ?></td>
            <td width="20"></td>
            <td>0,00 &euro;</td>
        </tr>
        <tr>
            <td colspan="3" height="1" bgcolor="bcbcbc"></td>
        </tr>
        <tr>
            <td height="25"><?php _e('TVA', THEME_TD); ?></td>
            <td class="width:20"></td>
            <td>0,00 &euro;</td>
        </tr>
        <tr>
            <td colspan="3" height="1" bgcolor="bcbcbc"></td>
        </tr>
        <tr>
            <td height="25"><?php _e('Total TTC', THEME_TD); ?></td>
            <td class="width:20"></td>
            <td>0,00 &euro;</td>
        </tr>
        <tr>
            <td colspan="3" height="1" bgcolor="bcbcbc"></td>
        </tr>
        </tbody>
    </table>
</div>
