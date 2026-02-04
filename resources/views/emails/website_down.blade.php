<!DOCTYPE html>
<html>

<head>
    <title>Website Down Alert</title>
</head>

<body>
    <h1>Website Down Alert</h1>
    <p>The following website is down:</p>
    <p><a href="{{ $monitoredSite->url }}">{{ $monitoredSite->url }}</a></p>
    <p>Please check it immediately.</p>
</body>

</html>