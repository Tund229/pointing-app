@extends('layouts.app')

@section('content')
    <style>
        h2,
        h5,
        .h2,
        .h5 {
            font-family: inherit;
            font-weight: 600;
            line-height: 1.5;
            margin-bottom: .5rem;
            color: #32325d;
        }

        h5,
        .h5 {
            font-size: .8125rem;
        }

        @media (min-width: 992px) {

            .col-lg-6 {
                max-width: 50%;
                flex: 0 0 50%;
            }
        }

        @media (min-width: 1200px) {

            .col-xl-3 {
                max-width: 25%;
                flex: 0 0 25%;
            }

            .col-xl-6 {
                max-width: 50%;
                flex: 0 0 50%;
            }
        }


        .bg-danger {
            background-color: #f5365c !important;
        }



        @media (min-width: 1200px) {

            .justify-content-xl-between {
                justify-content: space-between !important;
            }
        }


        .pt-5 {
            padding-top: 3rem !important;
        }

        .pb-8 {
            padding-bottom: 8rem !important;
        }

        @media (min-width: 768px) {

            .pt-md-8 {
                padding-top: 8rem !important;
            }
        }

        @media (min-width: 1200px) {

            .mb-xl-0 {
                margin-bottom: 0 !important;
            }
        }




        .font-weight-bold {
            font-weight: 600 !important;
        }


        a.text-success:hover,
        a.text-success:focus {
            color: #24a46d !important;
        }

        .text-warning {
            color: #fb6340 !important;
        }

        a.text-warning:hover,
        a.text-warning:focus {
            color: #fa3a0e !important;
        }

        .text-danger {
            color: #f5365c !important;
        }

        a.text-danger:hover,
        a.text-danger:focus {
            color: #ec0c38 !important;
        }

        .text-white {
            color: #fff !important;
        }

        a.text-white:hover,
        a.text-white:focus {
            color: #e6e6e6 !important;
        }

        .text-muted {
            color: #8898aa !important;
        }

        @media print {

            *,
            *::before,
            *::after {
                box-shadow: none !important;
                text-shadow: none !important;
            }

            a:not(.btn) {
                text-decoration: underline;
            }

            p,
            h2 {
                orphans: 3;
                widows: 3;
            }

            h2 {
                page-break-after: avoid;
            }

            @ page {
                size: a3;
            }

            body {
                min-width: 992px !important;
            }
        }

        figcaption,
        main {
            display: block;
        }

        main {
            overflow: hidden;
        }

        .bg-yellow {
            background-color: #ffd600 !important;
        }


        .icon {
            width: 3rem;
            height: 3rem;
        }

        .icon i {
            font-size: 2.25rem;
        }

        .icon-shape {
            display: inline-flex;
            padding: 12px;
            text-align: center;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
        }
    </style>
    @if (Auth::user()->role == 'admin')
        <h2 class="p-4 text-center">Statistiques générales</h2>
        <div class="header-body">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span class="h2 font-weight-bold mb-0"> {{ $pointing->count() }} </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">Pointages</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span class="h2 font-weight-bold mb-0">{{ $admin->count() }} </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-danger mr-2">Administrateurs</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span class="h2 font-weight-bold mb-0">{{ $tuteurs->count() }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2">Tuteurs</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span class="h4 font-weight-bold mb-0">{{ $tuteurs->sum('amount') }} </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">Budget(Francs CFA)</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->role == 'teacher')
        <div class="row justify-content-between ">
            <div class="col-md-6 mb-4">
                <div class="card card-outline-secondary">
                    <div class="card-body">
                        <h4 class="text-center">Pointez aujourd'hui !</h4>
                        <hr>
                        @if ($pointing)
                            <form action="{{ route('teacher.pointing.update', $pointing->id) }}" method="POST">
                                @method('PUT')
                            @else
                                <form action="{{ route('teacher.pointing.store') }}" method="POST">
                        @endif @csrf
                        <div class="form-group mb-4">
                            <label for="course_date">Date du cours</label>
                            <input class="form-control" id="course_date" type="date" name="course_date"
                                value="{{ $pointing->course_date ?? date('Y-m-d') }}">
                            @error('course_date')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="start_time">Heure de début</label>
                            <input class="form-control" id="start_time" type="time" name="start_time"
                                value="{{ $pointing->start_time ?? '' }}">
                            @error('start_time')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="end_time">Heure de fin</label>
                            <input class="form-control" id="end_time" type="time" name="end_time"
                                value="{{ $pointing->end_time ?? '' }}">
                            @error('end_time')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="course">Matières</label>
                            <select name="course_id" id="course" class="form-control">
                                <option value="" disabled selected>--Sélectionner une matière--</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $pointing && $pointing->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="promotion">Promotion</label>
                            <select name="promotion_id" id="promotion" class="form-control">
                                <option value="" disabled selected>--Sélectionner une promotion--</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}"
                                        {{ $pointing && $pointing->promotion_id == $promotion->id ? 'selected' : '' }}>
                                        {{ $promotion->name }}</option>
                                @endforeach
                            </select>
                            @error('promotion_id')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="comment">Vous avez fait quelle séquence,TP ou exercice</label>
                            <textarea name="comment" id="comment" class="form-control">{{ $pointing ? $pointing->comment : '' }}</textarea>
                            @error('comment')
                                <div class="text-danger text-center f-w-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-lg btn-block py-1" type="submit">Pointer</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4 ">
                <div class="header-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h2 font-weight-bold mb-0">{{ Auth::user()->amount }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2">Montant total(Francs CFA)</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h2 font-weight-bold mb-0">{{ Auth::user()->total_hours }}
                                                H</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-danger mr-2">Nombre d'heures</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
