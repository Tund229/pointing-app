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



        <h3 class="m-4 text-center text-primary">Liste des cours déposés</h3>

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="text-center">Créé le</th>
                                <th class="text-center">Matière</th>
                                <th class="text-center">Promotion</th>
                                <th class="text-center">Enseignants</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Valider /Rejeter</th>
                                <th class="text-center">Observation</th>
                                <th class="text-center">Télécharger</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($course_deposits as $course_deposit)
                                <tr>
                                    <td class="text-center">{{ $course_deposit->created_at }}</td>
                                    <td class="text-center">{{ $course_deposit->course->name }}</td>
                                    <td class="text-center">{{ $course_deposit->promotion->name }}</td>
                                    <td class="text-center">{{ $course_deposit->user->name }}</td>
                                    <td class="text-center">
                                        @if ($course_deposit->state === 'en attente')
                                            <span class="badge bg-warning text-dark">{{ $course_deposit->state }}</span>
                                        @elseif ($course_deposit->state === 'validé')
                                            <span class="badge bg-success text-white">{{ $course_deposit->state }}</span>
                                        @elseif ($course_deposit->state === 'rejété')
                                            <span class="badge bg-danger text-white">{{ $course_deposit->state }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.course-deposit.valide', $course_deposit->id) }}"
                                            class="btn btn-success mb-2">
                                            Valider
                                        </a>
                                        <a href="{{ route('admin.course-deposit.refuse', $course_deposit->id) }}"
                                            class="btn btn-danger">
                                            Rejeter
                                        </a>
                                    </td>

                                    <td class="text-center">{{ $course_deposit->comment ?? '-' }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.course-deposit.download', $course_deposit->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.course-deposit.destroy', $course_deposit->id) }}"
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
                    title: 'Supprimez ce cours déposé ?',
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



    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.delete-all-button').addEventListener('click', function() {
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Êtes-vous sûr de vouloir supprimer tous les cours déposés ? Cette action est irréversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer tout',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigez l'utilisateur vers la route de suppression
                        window.location.href = "{{ route('admin.course-deposit.delete-all') }}";
                    }
                });
            });
        });
    </script> --}}
@endsection
