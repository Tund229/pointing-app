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



        <h3 class="m-4 text-center text-primary">Liste des pointages</h3>
        <div class="col-12 col-lg-3 my-4">
            <button class="btn btn-danger col-12 delete-all-button">
                <i class="fa fa-trash"></i> Supprimer tout
            </button>
        </div>
        

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Date du cours</th>
                                <th class="text-center">Heure de début</th>
                                <th class="text-center">Heure de fin</th>
                                <th class="text-center">Tuteur</th>
                                <th class="text-center">Matière</th>
                                <th class="text-center">Promotion</th>
                                <th class="text-center">Obs</th>
                                <th class="text-center">Etat</th>
                                @if ($pointings->where('reason', '!=', null)->count() > 0)
                                    <th class="text-center">Motif</th>
                                @endif
                                <th class="text-center">Supprimer</th>
                                <th class="text-center">Voir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pointings as $pointing)
                                <tr>
                                    <td class="text-center">{{ $pointing->course_date }}</td>
                                    <td class="text-center">{{ $pointing->start_time }}</td>
                                    <td class="text-center">{{ $pointing->end_time }}</td>
                                    <td class="text-center">{{ $pointing->user->name }}</td>
                                    <td class="text-center">{{ $pointing->course->name }}</td>
                                    <td class="text-center">{{ $pointing->promotion->name }} </td>
                                    <td class="text-center">{{ $pointing->comment ?? '-' }} </td>
                                    <td class="text-center">
                                        @if ($pointing->state === 'en attente')
                                            <span class="badge bg-warning text-dark">{{ $pointing->state }}</span>
                                        @elseif ($pointing->state === 'validé')
                                            <span class="badge bg-success text-white">{{ $pointing->state }}</span>
                                        @elseif ($pointing->state === 'rejété')
                                            <span class="badge bg-danger text-white">{{ $pointing->state }}</span>
                                        @endif
                                    </td>

                                    @if ($pointings->where('reason', '!=', null)->count() > 0)
                                        <td class="text-center">
                                            @if ($pointing->reason)
                                                {{ $pointing->reason }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <form action="{{ route('admin.pointing.destroy', $pointing->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.pointing.show', $pointing->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
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
    <script>
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                Swal.fire({
                    title: 'Supprimez ce pointage ?',
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.delete-all-button').addEventListener('click', function() {
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Êtes-vous sûr de vouloir supprimer tous les pointages ? Cette action est irréversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer tout',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigez l'utilisateur vers la route de suppression
                        window.location.href = "{{ route('admin.pointings.delete-all') }}";
                    }
                });
            });
        });
    </script>
@endsection
