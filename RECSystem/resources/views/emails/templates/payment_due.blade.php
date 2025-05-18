<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Due Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #13264b; color: #ffffff;">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #1f345d; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.4);">
                    <tr>
                        <td align="left" style="padding: 40px 20px;">
                            <h2 style="color: #ffffff;">Payment Due Reminder</h2>
                            <p style="font-size: 16px;">Hi {{ $user->first_name }},</p>
                            <p>This is a friendly reminder that your payment is due. Kindly complete the payment soon to avoid any interruptions to your membership or access to our services.</p>
                            <p>You can make the payment through your account dashboard.</p>
                            @if(!empty($custom_message))
                            <p style="margin-top: 20px;">{{ $custom_message }}</p>
                            @endif
                            <p style="margin-top: 30px; font-size: 14px; color: #bbbbbb;">If you've already made the payment, please disregard this message.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
