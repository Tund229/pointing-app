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



        <h3 class="m-4 text-center text-primary">Liste de pointage</h3>
        <div class="col-12 col-lg-3 my-4">
            <a href="{{ route('home') }}" class="btn btn-success col-12 ">
                <i class="fa fa-plus"></i> Nouveau pointage
            </a>
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
                                <th class="text-center">Matière</th>
                                <th class="text-center">Promotion</th>
                                <th class="text-center">Obs</th>
                                <th class="text-center">Etat</th>
                                @if ($pointings->where('reason', '!=', null)->count() > 0)
                                    <th class="text-center">Motif</th>
                                @endif
                                <th class="text-center">Modifier</th>
                                <th class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pointings as $pointing)
                                <tr>
                                    <td class="text-center">{{ $pointing->course_date }}</td>
                                    <td class="text-center">{{ $pointing->start_time }}</td>
                                    <td class="text-center">{{ $pointing->end_time }}</td>
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
                                        <a href="{{ route('home', $pointing->id) }}" class="btn btn-primary pointing-link" data-state="{{ $pointing->state }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                    
                                    <td class="text-center">
                                        <form action="{{ route('teacher.pointing.destroy', $pointing->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-button"
                                                @if ($pointing->state === 'validé')
                                                    disabled
                                                @endif>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Parcourez tous les liens avec la classe "pointing-link"
    $('.pointing-link').each(function () {
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
