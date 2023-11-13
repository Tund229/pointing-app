@extends('layouts.app')

@section('content')
    <div class="page-body">
        @if (session('error_message'))
            <div class="alert alert-danger text-center  align-items-center">
                {{ session('error_message') }}
            </div>
        @endif

        @if (session('success_message'))
            <div class="alert alert-success text-center  align-items-center">
                {{ session('success_message') }}
            </div>
        @endif



        <h3 class="m-4 text-center text-primary">Liste des fiches de paie</h3>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Créé le</th>
                                <th class="text-center">Nom</th>
                                <th class="text-center">Mois</th>
                                <th class="text-center">Heures</th>
                                <th class="text-center">Montant</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Télécharger</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paySlips as $paySlip)
                                <tr>
                                    <td class="text-center">{{ $paySlip->created_at }}</td>
                                    <td class="text-center">{{ $paySlip->user->name }}</td>
                                    <td class="text-center">{{ $paySlip->month }}</td>
                                    <td class="text-center">{{ $paySlip->total_hours }}</td>
                                    <td class="text-center">{{ $paySlip->amount }}</td>
                                    <td class="text-center">{{ $paySlip->code }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('teacher.pay-slips.downloadPaySlips', $paySlip->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                   

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

  
@endsection
