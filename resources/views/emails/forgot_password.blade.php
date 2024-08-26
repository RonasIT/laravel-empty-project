@extends('layouts.mail')

@section('content')
    <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
           style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
        <!-- Body content -->
        <tr>
            <td class="content-cell"
                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                    Hello!</h1>
                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                    You are receiving this email because we received a password reset request for your account.</p>
                <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0"
                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                   style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                        <table border="0" cellpadding="0" cellspacing="0"
                                               style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <a href="{{ config('app.frontend_url') }}/enter-new-password/{{ $hash }}"
                                                       class="button button-blue" target="_blank"
                                                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">Reset
                                                        Password</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                    If you did not request a password reset, no further action is required.</p>
                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                    Regards,<br>{{ config('app.name') }}</p>
                <table class="subcopy" width="100%" cellpadding="0" cellspacing="0"
                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                    <tr>
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">
                                If you’re having trouble clicking the "Reset Password" button, copy and paste the URL
                                below into your web browser:
                                <a href="{{ config('app.frontend_url') }}/enter-new-password/{{ $hash }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">
                                    {{ config('app.frontend_url') }}/enter-new-password/{{ $hash }}
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
