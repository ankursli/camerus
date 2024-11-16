<?php

use App\Hooks\Salon;

$salons = Salon::getSalon();
if (!empty($salons)) {
    $salons = array_chunk($salons, 4);
}
?>

        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500,600,700,800,900" rel="stylesheet">
    <style>
        @page {
            size: auto;
            margin: 0;
            margin: 0mm;
            margin-top: 0.5cm;
            width: 100%;
        }

        @page :footer {
            display: none
        }

        @page :header {
            display: none
        }

        body {
            display: block;
            font-family: "Overpass";
            width: 100%;
            margin: 0;
        }

        #container {
            margin: 0 auto;
        }

        .w_190 {
            width: 176px;
        }

        .v_top {
            vertical-align: top;
        }

        .header_title {
            box-sizing: border-box;
            text-transform: none;
            padding: 0;
            margin: 0;
            font-family: inherit;
            line-height: 1;
            font-weight: 300;
            color: black;
            font-size: 21px;
            margin-bottom: 16px;
            font-family: "Overpass_light", Overpass;
        }

        .block-date {
            font-family: "Overpass_light", Overpass;
            list-style: none;
            box-sizing: border-box;
            font-size: 12px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #ad9575;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.2;
            display: block;

            font-size: 12px;
            height: 15px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .block-img-container {
            color: #58595B;
            font-size: 13px;
            line-height: 1.2em;
            font-family: "Overpass_thin", Overpass;
            list-style: none;
            text-align: right;
            box-sizing: border-box;
            display: block;
            position: relative;
            overflow: hidden;
            margin-bottom: 10px;
            width: 176px;
            height: 106px;
            text-align: center;

            margin-bottom: 5px;
        }

        .block-img-container img {
            height: 106px;
            width: auto;
            position: relative;
            /* object-fit: contain; */
        }

        .block-img {
            height: 106px;
            width: auto;
            position: relative;
            /* object-fit: contain; */
        }

        .block-title {
            list-style: none;
            text-align: center;
            box-sizing: border-box;
            font-family: "Overpass_thin", Overpass;
            color: #333;
            padding: 0;
            margin: 0;
            font-family: inherit;
            line-height: 1;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            margin-bottom: 5px;
            vertical-align: top;


            font-size: 14px;
            height: 28px;
            overflow: hidden;
            -ms-text-overflow: ellipsis;
            text-overflow: ellipsis;
        }

        .block-subtitle {
            font-weight: normal;
            font-size: 13px;
            font-family: "Overpass_light", Overpass;
            list-style: none;
            text-align: center;
            box-sizing: border-box;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            margin-bottom: 20px;
            color: #dcef02;
            padding-right: 0;
            display: block;
            line-height: 1.4;

            margin-bottom: 10px;
            font-size: 12px;
            height: 54px;
            overflow: hidden;
        }
    </style>
</head>

<body>
<table id="container" align="center" cellpadding="0" cellspacing="0" border="0" summary="">
    <tr>
        <td>
            <table id="header" cellpadding="0" cellspacing="0" border="0" summary="">
                <tr>
                    <td>
                        <img src="{{ get_template_directory_uri(). '/dist/images/header-logo.svg' }}"
                             srcset="{{ get_template_directory_uri(). '/dist/images/header-logo.svg' }}" width="100"
                             alt="">
                    </td>
                </tr>
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td>
                        <div class="header_title">
                            <?php _e('L\'agenda des salons', THEME_TD); ?>
                        </div>
                    </td>
                </tr>
            </table>
            @if(!empty($salons) && is_array($salons))
                @foreach($salons as $g_salons)
                    <table id="section1" cellpadding="0" cellspacing="0" border="0" summary="">
                        <tr>
                            @if(isset($g_salons) && !empty($g_salons) && is_array($g_salons))
                                @foreach($g_salons as $salon)
                                    @if(!empty($salon))
                                        <td class="w_190">

                                            <table class="block" cellpadding="0" cellspacing="0" border="0" summary="">
                                                <tr>
                                                    <td class="w_190">
                                                        <div class="block-date">
                                                            @if(isset($salon->salon_start_date) && !empty($salon->salon_start_date))
                                                                {{ strftime('%d', strtotime($salon->salon_start_date)) }}
                                                                @if(isset($salon->salon_end_date) && !empty($salon->salon_end_date))
                                                                    <?php _e('AU', THEME_TD) ?>
                                                                    {{ strftime('%d %B %Y', strtotime($salon->salon_end_date)) }}
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="block-img-container w_190">
                                                            @if(isset($salon->post_title) && !empty($salon->post_title))
                                                                <img class="block-img"
                                                                     src="{{ get_the_post_thumbnail_url($salon->ID, 'full') }}"
                                                                     alt="{{ $salon->post_title }}">
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="w_190 v_top">
                                                        <div class="block-title">
                                                            @if(isset($salon->post_title) && !empty($salon->post_title))
                                                                {!! $salon->post_title !!}
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                        @if(!$loop->last)
                                            <td width="5"></td>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </tr>
                    </table>
                @endforeach
            @endif
        </td>
    </tr>
</table>

</body>

</html>