<p style="margin: 0 0 16px;">Nouvelle demande de rappel reçue</p>

<p style="margin: 16px 0 16px; border-bottom: 2px solid #F40D42"></p>

<h2 style="color: #2d2e83; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 28px 0 15px; text-align: left;">
    Informations personnelles :</h2>
<p style="margin: 0 0 5px; color: #4e4e4e;">
    <b style="color: #272727;">Sujet: </b> {{ $subject }}</p>
<p style="margin: 0 0 5px; color: #4e4e4e;">
    <b style="color: #272727;">Page source: </b> {{ $referer }}</p>

@if(!empty($infos) && is_array($infos))
    @foreach($infos as $info)
        <p style="margin: 0 0 5px; color: #4e4e4e;">
            <b style="color: #272727;">{{ $info['placeholder'] }}
                : </b> {!! $info['value'] !!}</p>
    @endforeach
@endif

<p style="margin: 16px 0 16px;  text-align: center;"><?php _e('Merci de votre confiance', THEME_TD) ?>.</p>

<p style="margin: 0 0 16px;  text-align: center;"><b><?php _e("L’équipe de", THEME_TD) ?> {{ bloginfo('name') }}</b>
</p>