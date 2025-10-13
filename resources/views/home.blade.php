<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Pelanggan</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <h3><a class="logoL" href="/">{{ Auth::user()->name }}</a></h3>
        </div>
        <nav>
            <ul id="menu">
               <li>
                     <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <section>
        
    </section>
    
    
</body>
</html>