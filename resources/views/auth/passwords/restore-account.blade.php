<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Kodinger">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <title>Les tutoriel - Pointage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/my-login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">

</head>

<body class="my-login-page">
    <section class="h-100">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="card-wrapper">
                    <div class="card fat">
                        <div class="brand">
                            <img src="{{ asset('img/logo2.png') }}" alt="logo">
                        </div>
                        <div class="card-body">
                            @if (session('error_message'))
                                <div
                                    class="alert alert-danger text-center d-flex justify-content-between align-items-center">
                                    {{ session('error_message') }}
                                </div>
                            @endif

                            @if (session('success_message'))
                                <div
                                    class="alert alert-success text-center d-flex justify-content-between align-items-center">
                                    {{ session('success_message') }}
                                </div>
                            @endif
                            <h4 class="card-title text-center">Restaurer mot de passe</h4>
                            <form method="POST" action="{{ route('restore_account') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email">
                                    @error('email')
                                        <div class="text-danger text-center  f-w-400">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Restaurer
                                    </button>
                                </div>

                                <a href="{{ route('home') }}" class="text-center mb-2">
                                    Acceuil
                                </a>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer-->
    <footer class="bg-dark text-center py-2">
        <div class="container px-4">
            <div class="text-white small">
                <div class="mb-2">&copy; MCOPYRIGHT 2023| LES TUTORIELS@DENIS COLY. ALL RIGHTS RESERVED
                    <p class="text-white"></p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        let isPasswordVisible = false;

        passwordToggle.addEventListener('click', () => {
            isPasswordVisible = !isPasswordVisible;
            if (isPasswordVisible) {
                passwordInput.type = 'text';
                passwordToggle.innerHTML = '<i class="fa fa-eye-slash text-primary"></i>';
            } else {
                passwordInput.type = 'password';
                passwordToggle.innerHTML = '<i class="fa fa-eye text-primary"></i>';
            }
        });
    </script>
</body>

</html>
