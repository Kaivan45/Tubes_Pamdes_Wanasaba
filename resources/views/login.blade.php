<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PAMDES WANASABA</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h3 class="sambut">Selamat Datang di Website <br> Pamdes Wanasaba</h3>
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Login</h2>
               <form id="loginForm" action="{{ route('login.post') }}" method="POST">
                @csrf
                <input id="username" type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                <input id="password" type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>

                @if($errors->any())
                    <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
 