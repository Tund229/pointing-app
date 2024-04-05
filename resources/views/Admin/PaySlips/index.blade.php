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
        <h3 class="m-4 text-center text-primary">Liste des fiches de paies des tuteurs</h3>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('admin.pay-slips.create') }}" class="btn btn-success col-12 ">
                <i class="fa fa-plus"></i> Nouvelle Fiche
            </a>
        </div>

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
                                <th class="text-center">Admin</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Télécharger</th>
                                <th class="text-center">Supprimer</th>
                                <th class="text-center">Details</th>

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
                                    <td class="text-center">{{ $paySlip->admin->name }}</td>
                                    <td class="text-center">{{ $paySlip->code }}</td>
                                    <td class="text-center">
                                        @if ($paySlip->state == 1)
                                            <span class="badge bg-success text-white">Payé</span>
                                        @else
                                            <span class="badge bg-danger text-white">Non Payé</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.pay-slips.downloadPaySlips', $paySlip->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.pay-slips.destroy', $paySlip->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        @if ($paySlip->state == 1)
                                            <a href="{{ route('admin.pay-slips.show', $paySlip->id) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                Swal.fire({
                    title: 'Supprimez cette fiche de paie ?',
                    text: 'Cette action est irréversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endsection
