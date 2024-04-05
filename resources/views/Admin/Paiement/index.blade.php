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



        <h3 class="m-4 text-center text-primary">Liste des paiements en attente</h3>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('admin.paiement.employes') }}" class="btn btn-success col-12 ">
                Payer les employés fixes
            </a>
        </div>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('admin.paiement.admin') }}" class="btn btn-success col-12 ">
                Payer les admins
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
                                <th class="text-center">Payer</th>
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
                                        <form action="{{ route('admin.paiements.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="pay_slip_id" value="{{ $paySlip->id }}">
                                            <button type="submit" class="btn btn-success">Payer</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <h3 class="m-4 text-center text-primary">Liste des paiements</h3>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Créé le</th>
                                <th class="text-center">Nom</th>
                                <th class="text-center">Montant</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Balance précédente</th>
                                <th class="text-center">Balance actuelle</th>
                                <th class="text-center">Payé par</th>
                                <th class="text-center">Télécharger</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paiements as $paiement)
                                <tr>
                                    <td class="text-center">{{ $paiement->created_at }}</td>
                                    <td class="text-center">
                                        @if ($paiement->user_id !== null)
                                            {{ $paiement->user->name }}
                                        @elseif ($paiement->tuteur_fixe_id !== null)
                                            {{ $paiement->tuteurFixe->name }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $paiement->amount }}</td>
                                    <td class="text-center">
                                        @if ($paiement->status == 'SUCCESS')
                                            <span class="badge bg-success text-white">Succès</span>
                                        @elseif ($paiement->status == 'PENDING')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($paiement->status == 'FAILED')
                                            <span class="badge bg-danger text-white">Échec</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $paiement->previousBalance }}</td>
                                    <td class="text-center">{{ $paiement->currentBalance }}</td>
                                    <td class="text-center">{{ $paiement->payeBy->name }}</td>
                                    <td class="text-center">
                                        @if ($paiement->success == 1)
                                            <a href="{{ route('admin.paiement.downloadPaySlips', $paiement->id) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.paiement.destroy', $paiement->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
