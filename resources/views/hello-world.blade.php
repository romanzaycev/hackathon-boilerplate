<!DOCTYPE html>
<html>
<head>
    <title>Lumen</title>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
</head>
<body>
<div class="container">
    <nav class="nav">
        <div class="nav-center">
            <span class="brand" style="font-size: 4.75em;">
                🤔
            </span>
        </div>
    </nav>

    <div class="row">
        <div class="col"><h1>Hello, {{ $name }}!</h1></div>
    </div>
    <div class="row">
        <div class="col">
            <div>{{ $version }}</div>
        </div>
    </div>
</div>
</body>
</html>