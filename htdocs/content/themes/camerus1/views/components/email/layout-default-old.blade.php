<?php
/**
 * Mail content configuration
 */
$text_color = '#000';
$title_color = '#58585a';
$head_bg = '#ff560d';
$line_color = '#ff560d';
$att = get_field('app_header_logo', 'option');
if (empty($att)) {
    $att = get_template_directory_uri().'/dist/images/Logo_Cameru-450px.png';
}
?>

<html lang="fr-FR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ bloginfo('name') }}</title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<div id="wrapper" dir="ltr"
     style="background-color: #f7f7f7; margin: 0; padding: 70px 0 70px 0; width: 100%; -webkit-text-size-adjust: none;">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
        <tr>
            <td align="center" valign="top">
                <div id="template_header_image">
                    <p style="margin-top: 0;">
                        <a href="{{ home_url() }}">
                            <img src="{{ $att }}"
                                 alt="{{ bloginfo('name') }}"
                                 style="border: none; display: inline-block; font-size: 14px; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; vertical-align: middle; margin-right: 10px;">
                        </a>
                    </p></div>
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container"
                       style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-radius: 3px;">
                    <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <!-- Header -->
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header"
                                   style="background-color: {{ $head_bg }}; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;">
                                <tbody>
                                <tr>
                                    <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                        <h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: center; text-shadow: 0 1px 0 #57589c;">
                                            {!! $subject !!}</h1>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- End Header -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- Body -->

                        @if($email_type === 'advice')

                            @include('components.email.content-advice')

                        @endif

                        @if($email_type === 'callback')

                            @include('components.email.content-callback')

                        @endif

                        @if($email_type === 'contact')

                            @include('components.email.content-contact')

                        @endif

                        @if($email_type === 'dotation_stock')

                            @include('components.email.content-dotation-stock')

                        @endif

                        @if($email_type === 'procustomer-sign')

                            @include('components.email.content-pro-customer-sign')

                        @endif

                        @if($email_type === 'procustomer-validate')

                            @include('components.email.content-pro-customer-validate')

                        @endif

                        @if($email_type === 'procustomer-manager-validation')

                            @include('components.email.content-pro-customer-manager-validation')

                        @endif

                        @if($email_type === 'procustomer-order')

                            @include('components.email.content-pro-customer-order')

                        @endif

                        @if($email_type === 'default')

                            @include('components.email.content-default')

                        @endif
                        <!-- End Body -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- Footer -->
                            <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                                <tbody>
                                <tr>
                                    <td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            <tbody>
                                            <tr>
                                                <td colspan="2" valign="middle" id="credit"
                                                    style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #8182b5; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;">
                                                    <p>{{ bloginfo('name') }}<br><?php _e('Powered by', THEME_TD) ?>
                                                        <a href="{{ home_url() }}"
                                                           style="color: {{ $head_bg }}; font-weight: normal; text-decoration: underline;">
                                                            {{ bloginfo('name') }}</a></p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- End Footer -->
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>


</body>
</html>