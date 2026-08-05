<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @media only screen and (max-width: 600px) {

            .container {
                width: 100% !important;
            }

            .content {
                padding: 20px !important;
            }

            .responsive-table,
            .responsive-table tbody,
            .responsive-table tr,
            .responsive-table td {
                display: block !important;
                width: 100% !important;
            }

            .responsive-table td {
                box-sizing: border-box;
                border-bottom: none !important;
            }

            .btn {
                display: block !important;
                width: 100% !important;
                text-align: center !important;
                box-sizing: border-box;
            }

            .break-word {
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .heading {
                font-size: 20px !important;
            }
        }
    </style>
</head>

<body style="margin:0;padding:20px;background:#f5f7fa;font-family:Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table class="container" width="640" cellpadding="0" cellspacing="0"
                    style="width:640px;max-width:640px;background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">

                    <tr>
                        <td class="heading"
                            style="background:#0f172a;color:#ffffff;padding:20px 30px;font-size:24px;font-weight:bold;">
                            Mughal Estate Developers
                        </td>
                    </tr>

                    <tr>
                        <td class="content" style="padding:30px;">

                            <p style="font-size:16px;margin-top:0;">
                                Dear {{ $user->name_en ?? $user->email }},
                            </p>

                            <p style="line-height:1.7;">
                                Your account has been created successfully.
                                Below are your login details for accessing the system.
                            </p>

                            <table class="responsive-table" width="100%" cellpadding="0" cellspacing="0"
                                style="border-collapse:collapse;margin-top:20px;">

                                <tr>
                                    <td
                                        style="padding:12px;border:1px solid #e5e7eb;background:#f8fafc;font-weight:bold;width:35%;">
                                        Username
                                    </td>

                                    <td class="break-word" style="padding:12px;border:1px solid #e5e7eb;">
                                        {{ $user->email }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border:1px solid #e5e7eb;background:#f8fafc;font-weight:bold;">
                                        Password
                                    </td>

                                    <td class="break-word" style="padding:12px;border:1px solid #e5e7eb;">
                                        {{ $password }}
                                    </td>
                                </tr>

                            </table>

                            <p style="margin-top:25px;">
                                Please log in using the link below:
                            </p>

                            <a href="{{ $loginUrl }}" class="btn"
                                style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:6px;font-weight:bold;">
                                Login to System
                            </a>

                            <p style="margin-top:30px;color:#475569;line-height:1.7;font-size:14px;">
                                For security reasons, please do not share your login credentials with anyone.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td
                            style="background:#f8fafc;padding:15px 30px;border-top:1px solid #e5e7eb;font-size:12px;color:#64748b;">
                            This is an automated system email. Please do not reply to this message.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
