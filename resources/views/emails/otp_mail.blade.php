<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP mail</title>

    <style>
        .brand {
            background: #1d4a35 !important;
            color: white;
            padding: 10px 0 10px 10px;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
        <div style="margin:50px auto;width:70%;padding:20px 0">
            
            <p style="font-size:1.1em">Hi,</p>
            <p>Thank you for choosing Your Brand. Use the following OTP to complete your Sign Up procedures. OTP is
                valid for 5 minutes</p>
            <h2
                style="background: #1d4a35;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
                OTP: {{ $otp }}
            </h2>
        </div>
    </div>
</body>

</html>