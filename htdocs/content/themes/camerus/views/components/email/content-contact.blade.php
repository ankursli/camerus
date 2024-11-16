<p><?php _e('Nouvelle demande de contact reçue', THEME_TD) ?></p>

<p style="margin: 16px 0 16px; border-bottom: 2px solid {{ $line_color }}"></p>

<p>Informations personnelles :</p>

@if(!empty($infos) && is_array($infos))
    @foreach($infos as $info)
        @if(array_key_exists('placeholder', $info) && !empty($info['placeholder']))
            <p><b style="color: #272727;">{{ $info['placeholder'] }} : </b> {!! $info['value'] !!}</p>
        @endif
    @endforeach
@endif