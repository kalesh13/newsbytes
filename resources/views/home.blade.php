<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Newsbytes marketing urls shortner</title>
    <link rel="stylesheet" href="{{mix('/css/app.css')}}" type="text/css" media="all"/>
</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script src="{{mix('/js/manifest.js')}}"></script>
    <script src="{{mix('/js/vendor.js')}}"></script>
    <script src="{{mix('/js/app.js')}}"></script>
</body>
</html>