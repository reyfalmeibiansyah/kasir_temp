<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('Assets/img/wikrama-logo.png') }}" rel="icon">
    <title>SuperMarket Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .login-container {
            display: flex;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }

        .login-form {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .btn-login {
            background-color: #212529;
            color: white;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #343a40;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 15px;
        }

        .social-login button {
            width: 100%;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h5 class="text-center">Welcome Back 👋</h5>
            <p class="text-center">Today is a new day. It's your day. You shape it.<br>Sign in to start managing your projects.</p>
            
            <!-- Error Modal -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-exclamation-circle"></i> Error</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('login-proses') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email')}}" class="form-control" required autofocus placeholder="Example@email.com">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="At least 8 characters">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <div class="text-right">
                    <a href="#" class="text-primary">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-login btn-block mt-3">Login</button>
                <div class="text-center mt-3">
                    Don't have an account? <a href="#" class="text-primary">Sign up</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            @if ($errors->any())
                $('#errorModal').modal('show');
            @endif
        });
    </script>
</body>

</html>