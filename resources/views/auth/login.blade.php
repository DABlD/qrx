<!doctype html>
<html lang="en">

<!-- Mirrored from qr-transit.onehealthnetwork.com.ph/login by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 19 Nov 2022 02:36:49 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="gPJ2CFeKyObXQsBV62dPiXZDJMHM0LDiGkTHr04n">

    <title>QRX | Login Page</title>

    <!-- Scripts -->
    <script src="{{ asset("qrx/js/app.js") }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="http://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset("qrx/css/app.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <style>
        @media (min-width: 992px) {
            #app > div {
                width: 50%;
            }
        }

        .auth-background {
            background-image: url("{{ asset($theme['login_banner_img'] ?? "qrx/img/FRONT1.jpg") }}");
            background-size: contain;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <div id="app" class="d-flex min-vh-100">
        <div class="auth-background flex-fill d-lg-block d-none"></div>

        <div class="align-self-center flex-fill py-3">
            <div class="container-fluid">
                <a href="{{ route('login') }}">
                    <img src="{{ asset($theme['login_bg_img'] ?? "qrx/img/FRONT2.jpg") }}" class="mx-auto mb-4 d-block" width="314" {{-- height="78" --}} alt="logo">
                </a>

                <div class="row justify-content-center">
                    <div class="col-sm-6 col-10">
                            <h1 class="mb-3 h3">Login</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="username" class="text-uppercase font-weight-bold mb-0 small">Username</label>

            <input id="username" type="username" class="form-control " name="username" value="" required autocomplete="username" autofocus>

                    </div>

        <div class="form-group">
            <label for="password" class="text-uppercase font-weight-bold mb-0 small">Password</label>

            <input id="password" type="password" class="form-control " name="password" required autocomplete="current-password">

                    </div>

        {{-- <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>
        </div> --}}

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Login
            </button>

                {{-- <a class="btn btn-link" href="password/reset.html">
                    Forgot Your Password?
                </a> --}}
                    </div>    
    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
        <script>
            @if($errors->all())
                @if($errors->has('validate'))
                    @php
                        $key = base64_encode($errors->get('key')[0]);
                    @endphp
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ $errors->get('validate')[0] }}",
                        html: `
                            Click <a href="{{ route('sendVerification', ['key' => $key]) }}" target="_blank">here</a> to verify
                        `
                    })
                @else
                    Swal.fire({
                        icon: 'error',
                        html: `
                            @foreach ($errors->all() as $key => $error)
                                {{ $error }}<br/>
                            @endforeach
                        `,
                    });
                @endif
            @elseif(Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: "{{ Session::get('success') }}",
                })
            @endif
        </script>
</body>

</html>
