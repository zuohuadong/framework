<!doctype html>
<html>
<head>
    <title>安装 Notadd</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <style>
        body {
            background: #fff;
            margin: 0;
            padding: 0;
        }
        body,
        input,
        button,
        select {
            color: #7e96b3;
            font-family: "Microsoft Yahei", "Open Sans", Helvetica, Arial, sans-serif;
            font-size: 16px;
        }
        .container {
            max-width: 515px;
            margin: 0 auto;
            padding: 60px 30px;
            text-align: center;
        }
        a {
            color: #e7652e;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        h1 {
            margin-bottom: 20px;
        }
        h2 {
            color: #3c5675;
            font-size: 28px;
            font-weight: normal;
            margin-bottom: 0;
        }
        form {
            margin-top: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group .form-field:first-child input {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .form-group .form-field:last-child input {
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .form-field input,
        .form-field select {
            background: #edf2f7;
            border: 2px solid transparent;
            box-sizing: border-box;
            height: 44px;
            line-height: 44px;
            margin: 0 0 1px;
            padding: 0 15px 0 180px;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
            width: 100%;
        }
        .form-field input:focus,
        .form-field select:focus {
            background: #fff;
            border-color: #e7652e;
            color: #444;
            outline: none;
        }
        .form-field label {
            float: left;
            font-size: 14px;
            height: 20px;
            line-height: 20px;
            margin-right: -160px;
            margin-top: 12px;
            opacity: 0.7;
            pointer-events: none;
            position: relative;
            text-align: right;
            width: 160px;
        }
        button {
            background: #3C5675;
            border: 0;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 40px;
            -webkit-appearance: none;
        }
        button[disabled] {
            opacity: 0.5;
        }
        #error {
            background: #d83e3e;
            border-radius: 4px;
            color: #fff;
            margin-bottom: 20px;
            padding: 10px 20px;
            text-align: left;
        }
        .info {
            background: #356196 !important;
        }
        #error p {
            margin: 6px 0;
        }
        #error p:first-child {
            margin-top: 0;
        }
        #error p:last-child {
            margin-bottom: 0;
        }
        .animated {
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation-duration: 0.5s;
            animation-duration: 0.5s;
            animation-delay: 0.5s;
            -webkit-animation-delay: 0.5s;
        }
        @-webkit-keyframes fadeIn {
            0% {
                opacity: 0
            }
            100% {
                opacity: 1
            }
        }
        @keyframes fadeIn {
            0% {
                opacity: 0
            }
            100% {
                opacity: 1
            }
        }
        .fadeIn {
            -webkit-animation-name: fadeIn;
            animation-name: fadeIn;
        }
        .errors {
            margin-top: 50px;
        }
        .errors .error:first-child {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .errors .error:last-child {
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .error {
            background: #EDF2F7;
            margin: 0 0 1px;
            padding: 20px 25px;
            text-align: left;
        }
        .error-message {
            font-size: 16px;
            color: #3C5675;
            font-weight: normal;
            margin: 0;
        }
        .error-detail {
            font-size: 13px;
            margin: 5px 0 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo file_get_contents(__DIR__ . '/logo.svg'); ?></h1>
    <div class="animated fadeIn"><?php echo $content; ?></div>
</div>
</body>
</html>