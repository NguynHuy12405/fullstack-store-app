<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .mail-box {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
        }
        h2 {
            text-align: center;
            letter-spacing: 1px;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .brand {
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="mail-box">
        <h2>THANK YOU</h2>
        <p>Xin chào <strong>{{ $data['name'] }}</strong>,</p>

        <p>
            Cảm ơn bạn đã liên hệ với cửa hàng thời trang của chúng tôi.  
            Chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất.
        </p>

        <p>
            <strong>Nội dung bạn gửi:</strong><br>
            {{ $data['message'] }}
        </p>

        <div class="brand">
            FASHION SHOP
        </div>
    </div>
</body>
</html>
