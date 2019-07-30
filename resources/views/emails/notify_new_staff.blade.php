<html>

<head>
    <style>
        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 120px;
        }
        
        footer {
            position: fixed;
            bottom: -110px;
            left: 0px;
            right: 0px;
            background-color: #E4E8F3;
            height: 100px;
        }
        
        body {
            font-family: "Proxima-Nova", "Helvetica Neue", "Helvetica", Sans-Serif;
        }
    </style>
</head>

<body>
    <header>
        <div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <div>
        <span style="font-size:1.6em;font-weight:bold;">Hello {{$staff->f_name ?? ''}},</span>
        <br/>
        <br/>
        <p>
            Your account on GIFMS has been created. Your password has been set to <span style="font-family:monospace;">'{{$password}}'</span>. Proceed to <a href="{{$js_url}}">login</a> and change your password.
        </p>

    </div>
</body>

</html>