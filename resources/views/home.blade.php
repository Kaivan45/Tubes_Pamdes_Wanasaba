<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Pelanggan</title>
</head>
<body>
    
    <form action="{{ route('logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit">
            Logout
        </button>
    </form>
    
</body>
</html>