<h3><?php _e('L’évenement', THEME_TD) ?></h3>
<div class="address">
    <p>
        <b>Nom de l’évenement: </b>{!! $order->get_meta('nom_evenement') !!}<br>
        <b>Date début: </b>{!! $order->get_meta('date_evenement') !!}<br>
        <b>Date fin: </b>{!! $order->get_meta('date_fin_evenement') !!}<br>
        <b>Lieu: </b>{!! $order->get_meta('lieu_evenement') !!}<br>
        <b>Ville: </b>{!! $order->get_meta('ville_evenement') !!}<br>
        <b>Type de l'évenement: </b>{!! $order->get_meta('event_type') !!}<br>
        @if($order->get_meta('event_type') == 'event')
            <b>Devis: </b> OUI<br>
        @else
            <b>Devis: </b> NON<br>
        @endif
    </p>
    <p>
        <b>Nom du stand: </b>{!! $order->get_meta('nom_stand') !!}<br>
        <b>Hall: </b>{!! $order->get_meta('hall_stand') !!}<br>
        <b>Allée: </b>{!! $order->get_meta('allee_stand') !!}<br>
        <b>Numéro de stand: </b>{!! $order->get_meta('numero_de_stand') !!}<br>
    </p>
</div>