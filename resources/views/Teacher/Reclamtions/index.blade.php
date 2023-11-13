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



        <h3 class="m-4 text-center text-primary">Liste des reclamations</h3>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('teacher.reclamations.create') }}" class="btn btn-success col-12 ">
                <i class="fa fa-plus"></i> Nouvelle reclamation
            </a>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Créé le</th>
                                <th class="text-center">titre</th>
                                <th class="text-center">description</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Modifier</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reclamations as $reclamation)
                                <tr>
                                    <td class="text-center">{{ $reclamation->created_at }}</td>
                                    <td class="text-center">{{ $reclamation->title }}</td>
                                    <td class="text-center">{{ $reclamation->description }}</td>
                                    <td class="text-center">
                                        @if ($reclamation->state === 'en attente')
                                            <span class="badge bg-warning text-dark">{{ $reclamation->state }}</span>
                                        @elseif ($reclamation->state === 'validé')
                                            <span class="badge bg-success text-white">{{ $reclamation->state }}</span>
                                        @elseif ($reclamation->state === 'rejété')
                                            <span class="badge bg-danger text-white">{{ $reclamation->state }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('teacher.reclamations.show', $reclamation->id) }}"
                                            class="btn btn-primary pointing-link" data-state="{{ $reclamation->state }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('teacher.reclamations.destroy', $reclamation->id) }}"
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
                    title: 'Supprimez cette réclamation ?',
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Parcourez tous les liens avec la classe "pointing-link"
            $('.pointing-link').each(function() {
                // Récupérez l'état à partir de l'attribut "data-state"
                var state = $(this).data('state');

                // Désactivez le lien si l'état est "validé"
                if (state === 'validé') {
                    $(this).addClass('disabled');
                    $(this).removeAttr('href'); // Supprimez l'attribut "href"
                }
            });
        });
    </script>
@endsection
