<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example Template</title>
</head>
<body>
    <h1>Welcome, {{ $username }}!</h1>
    @if (!empty($items))
        <ul>
            @foreach ($items as $key => $item)
                <li>{{ $key }} - {{ $item }} @if ($key % 2 == 0) - opa @endif</li>
            @endforeach
        </ul>
    @else
        <p>No items found.</p>
    @endif
</body>
</html>
