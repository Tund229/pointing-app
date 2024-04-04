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



        <h3 class="m-4 text-center text-primary">Liste des tuteurs Fixes</h3>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('admin.tuteurs-fixe.create') }}" class="btn btn-success col-12 ">
                <i class="fa fa-plus"></i> Nouveau tuteur fixe
            </a>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Nom</th>
                                <th class="text-center">Téléphone</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Poste</th>
                                <th class="text-center">Réseau</th>
                                <th class="text-center">Montant</th>
                                <th class="text-center">Modifier</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tuteurFixes as $tuteurFixe)
                                <tr>
                                    <td class="text-center">{{ $tuteurFixe->name }}</td>
                                    <td class="text-center">{{ $tuteurFixe->phone }}</td>
                                    <td class="text-center">
                                        @if ($tuteurFixe->state == 1)
                                            Actif
                                        @else
                                            Inactif
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $tuteurFixe->poste }}</td>
                                    <td class="text-center">
                                        {{ $tuteurFixe->reseau ? str_replace(['_', 'CASHIN'], ' ', $tuteurFixe->reseau) : '-' }}
                                    </td>
                                    <td class="text-center">{{ $tuteurFixe->amount }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.tuteurs-fixe.show', $tuteurFixe->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.tuteurs-fixe.destroy', $tuteurFixe->id) }}"
                                            method="POST">
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
                    title: 'Supprimez ce tuteur ?',
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
