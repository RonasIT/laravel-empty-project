<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        a:hover { opacity: 0.7; transition: all 0.1s; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #e8e8e8;">

<table role="presentation" border="0" cellpadding="0" cellspacing="0"
       style="max-width: 600px; margin: 0 auto; padding: 0; font: 16px Helvetica, Arial, sans-serif; line-height: 1.56;
         color: #363636; background-color: #ffffff;">
    <tr>
        <td style="padding: 30px 20px; border-bottom: solid 1px #e8e8e8;">
            <a href="http://site.piasa.fr/" target="_blank" style="display: block; max-width: 137px;">
                <img src="http://site.piasa.fr/static/img/logo-piasa.png" alt="Piasa Logo" border="0" width="137" height="31"
                     style="display: block; outline:none; text-decoration:none; max-width: 100%;">
            </a>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <h1 style="font: 40px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700; margin: 0.67em 0;
            mso-line-height-rule: exactly;">
                {{__('emails.hello')}}
            </h1>
            <p style="margin: 1em 0;">
                {{__('emails.forgot_password_text')}}
            </p>
            <p style="margin: 1em 0;">
                <a href="{{config('app.frontend_host')}}/{{$locale}}/restore-password?token={{$hash}}" target="_blank"
                   style="font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-text-size-adjust: none;
                   display: inline-block; height: 51px; line-height: 47px; font-size: 18px; font-weight: 700;
                   text-align: center; background-color: #363636; color: #ffffff; border-width: 2px; border-style: solid;
                   border-color: #363636; padding: 0 12px; text-decoration: none; box-sizing: border-box;">{{__('emails.reset_password')}}</a>
            </p>
            <p style="margin: 1em 0;">
                {{__('emails.you_can_ignore_it')}}
            </p>
            <p style="margin: 1em 0;">
                {{__('emails.thank_you')}}
            </p>
            <p style="margin: 1em 0;">
                {{__('emails.regards')}}<br>{{__('emails.piasa_team')}}
            </p>
            <p style="margin: 1em 0;">
                {{__('emails.click_to_direct_link')}}:
                <a href="{{config('app.frontend_host')}}/{{$locale}}/restore-password?token={{$hash}}"
                    style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                    text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">
                    {{config('app.frontend_host')}}/{{$locale}}/restore-password?token={{$hash}}
                </a>
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <table border="0" cellpadding="0" cellspacing="0"
                   style="width: 100%; max-width: 300px; margin: 0; padding: 0;
               font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700; line-height: 22px;">
                <tr>
                    <td>
                        <h3 style="margin: 1em 0; mso-line-height-rule: exactly;">Piasa</h3>
                    </td>
                    <td>
                        <h3 style="margin: 1em 0; mso-line-height-rule: exactly;">Calendrier</h3>
                    </td>
                </tr>
                <tr>
                    <td>—</td>
                    <td>—</td>
                </tr>
                <tr>
                    <td>
                        <a href="https://goo.gl/maps/aUgC4YbTgPy" target="_blank"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">118 rue du faubourg</a>
                    </td>
                    <td>
                        <a href="http://site.piasa.fr/fr/past-auctions/2018" target="_blank"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">Ventes passées</a>
                    </td>
                </tr>
                <tr>
                    <td>Saint-Honoré 75008 Paris</td>
                    <td>
                        <a href="http://site.piasa.fr/en/future-auctions" target="_blank"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">Ventes futures</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="tel:+330153341010"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">+33 (0)1 53 34 10 10</a>
                    </td>
                    <td>
                        <a href="http://site.piasa.fr/fr/news" target="_blank"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">Actualités</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="www.piasa.fr" target="_blank"
                           style="color: #363636; font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: 700;
                 text-decoration: none; line-height: 22px; -webkit-text-size-adjust: none;">www.piasa.fr</a>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding: 20px 0;">
                        <a href="www.piasa.fr" target="_blank"
                           style="display: inline-block; vertical-align: middle; font-weight: 700; text-decoration: none;
                 font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 22px;
                 -webkit-text-size-adjust: none; color: #363636; margin-right: 20px;">
                            <img src="http://site.piasa.fr/static/img/email/icon-facebook.png" alt="Facebook" width="10"
                                 style="display: block; outline:none; text-decoration:none;">
                        </a>
                        <a href="www.piasa.fr" target="_blank"
                           style="display: inline-block; vertical-align: middle; font-weight: 700; text-decoration: none;
                 font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 22px;
                 -webkit-text-size-adjust: none; color: #363636; margin-right: 20px;">
                            <img src="http://site.piasa.fr/static/img/email/icon-instagram.png" alt="Instagram" width="18"
                                 style="display: block; outline:none; text-decoration:none;">
                        </a>
                        <a href="www.piasa.fr" target="_blank"
                           style="display: inline-block; vertical-align: middle; font-weight: 700; text-decoration: none;
                 font: 12px 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 22px;
                 -webkit-text-size-adjust: none; color: #363636;">
                            <img src="http://site.piasa.fr/static/img/email/icon-pinterest.png" alt="Pinterest" width="15"
                                 style="display: block; outline:none; text-decoration:none;">
                        </a>
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>