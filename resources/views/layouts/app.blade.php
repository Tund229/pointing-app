<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Les tutoriels-Pointage') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="icon" href="{{ asset('img/logo.ico/') }}" type="image/x-icon">

</head>

<body>
    <style>
        #dataTable_paginate {
            margin: 0 auto;
            text-align: center;
            margin-bottom: 20px;
        }

        #dataTable {
            padding: 20px 0px;
        }

        #dataTable thead th {
            background-color: #f5f5f5;
            font-weight: bold;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        #dataTable tbody td {
            border-bottom: 1px solid #2251a0;
            padding: 8px;
        }

        #dataTable_paginate .paginate_button {
            background-color: transparent;
            color: #2251a0;
            border: 1px solid #2251a0;
            padding: 3px 8px;
            margin-right: 2px;
            transition: background-color 0.3s;
            cursor: pointer;

        }

        #dataTable_paginate .paginate_button.current {
            background-color: #2251a0;
            color: #fff;
            border: 1px solid #2251a0;
            padding: 3px 8px;
            margin-right: 2px;
        }

        #dataTable_filter {
            text-align: center;
            margin: 0 auto;
        }

        #dataTable_filter>label>input[type="search"] {
            padding: 0.4375rem 0.875rem;
            font-size: 0.9375rem;
            font-weight: 400;
            line-height: 1.53;
            color: #697a8d;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d9dee3;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        #dataTable_filter>label>input[type="search"]:focus {
            outline: 1px solid #2251a0;
            padding: 0.4375rem 0.875rem;
            font-size: 0.9375rem;
            font-weight: 400;
            line-height: 1.53;
            color: #697a8d;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d9dee3;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light ">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo2.png') }}" alt="logo" width="100px">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item ">
                                <a id="navbarDropdown" class="nav-link" href="{{ route('home') }}">
                                    <i class="fas fa-home text-primary"></i> {{ __('Acceuil') }}
                                </a>
                            </li>

                            @if (Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    <a id="navbarDropdown" class="nav-link"
                                        href="{{ route('admin.restore-account.index') }}">
                                        <i class="fas fa-sync-alt text-primary"></i>
                                        {{ __('Restaurer') }}
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item ">
                                <a id="navbarDropdown" class="nav-link" href="{{ route('profile') }}">
                                    <i class="fas fa-user text-primary"></i> {{ __('Profil') }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>


                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>

                <ul class="navbar-nav">
                    @if (Auth::user()->role == 'admin')
                        <li class="navbar-brand">
                            <a class="nav-link" href="{{ route('admin.course-deposit.index') }}">
                                <i class="fas fa-bell text-danger"></i>
                                <span class="badge-notif">{{ $courseDeposit }}</span>
                            </a>
                        </li>
                    @elseif(Auth::user()->role == 'teacher')
                        <li class="navbar-brand">
                            <a class="nav-link" href="{{ route('teacher.course-deposit.index') }}">
                                <i class="fas fa-bell text-danger"></i>
                                <span class="badge-notif">{{ $courseDeposit }}</span>
                            </a>
                        </li>
                    @endif
                    <style>
                        /* Add this to your CSS file or style section */
                        .nav-link {
                            position: relative;
                        }

                        .badge-notif {
                            position: absolute;
                            top: 0;
                            right: 0;
                            background-color: #2251a0;
                            border-radius: 50%;
                            height: 20px;
                            font-size: small;
                            width: 20px;
                            text-align: center;
                            line-height: 20px;
                            color: white;
                        }
                    </style>
                </ul>


            </div>
        </nav>
        @if (Auth::user()->role == 'admin')
            <div class="container">
                <div class="row text-center mt-4">
                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.teacher.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Tuteurs</h6>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.pointing.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Pointages</h6>
                            </a>
                        </div>
                    </div>


                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.pay-slips.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Fiche de paie</h6>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.course-deposit.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Cours déposés</h6>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row text-center mt-4">
                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.courses.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Matières</h6>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.promotion.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Promotions</h6>
                            </a>
                        </div>
                    </div>


                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.admin.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Administrateurs</h6>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.reclamations.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Reclamations</h6>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-4">
                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('admin.tuteurs-fixe.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Tuteurs Fixes</h6>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @elseif(Auth::user()->role == 'teacher')
            <div class="container">
                <div class="row text-center mt-4">
                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('teacher.pointing.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Voir les heures</h6>
                            </a>
                        </div>
                    </div>



                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('teacher.reclamations.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Réclamations</h6>
                            </a>
                        </div>
                    </div>


                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('teacher.pay-slips.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Fiche de paie</h6>
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 py-2">
                        <div class="card rounded bg-primary text-white border-0">
                            <a href="{{ route('teacher.course-deposit.index') }}" class="text-decoration-none">
                                <h6 class="card-header text-md text-white">Cours déposés</h6>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        @endif
        <main class="container my-4" style="padding-bottom: 60px;">
            @yield('content')
        </main>


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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [
                [0, "desc"]
            ],
            "language": {
                "sEmptyTable": "Aucune donnée disponible",
                "sInfo": " _START_ à _END_ sur _TOTAL_ ",
                "sInfoEmpty": " 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sLoadingRecords": "Chargement...",
                "sProcessing": "Traitement...",
                "sSearch": "Rechercher:",
                "sZeroRecords": "Aucun élément correspondant trouvé",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sLast": "Dernier",
                    "sNext": "Suiv.",
                    "sPrevious": "Préc."
                },
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                templateSelection: function(selection) {
                    return $('<span>').css('color', '#5c7ee5').text(selection.text);
                },
                dropdownPosition: 'below',
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>


</html>
