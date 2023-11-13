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



        <h3 class="m-4 text-center text-primary">Liste des demande de restaurations</h3>

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Créé le</th>
                                <th class="text-center">Nom</th>
                                <th class="text-center">Téléphone</th>
                                <th class="text-center">email</th>
                                <th class="text-center">Valider</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restoreAccounts as $restoreAccount)
                                <tr>
                                    <td class="text-center">{{ $restoreAccount->created_at }}</td>
                                    <td class="text-center">{{ $restoreAccount->name }}</td>
                                    <td class="text-center">{{ $restoreAccount->phone }}</td>
                                    <td class="text-center">{{ $restoreAccount->email }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.restore_account_valide', $restoreAccount->id) }}"
                                            class="btn btn-success">
                                            Valider
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.restore-account.destroy', $restoreAccount->id) }}"
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
                    title: 'Supprimez cette restauration de compte  ?',
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
