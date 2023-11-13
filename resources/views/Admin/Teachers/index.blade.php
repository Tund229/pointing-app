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



        <h3 class="m-4 text-center text-primary">Liste des tuteurs</h3>
        <div class="col-12 col-lg-3 my-4">
          <a href="{{route('admin.teacher.create')}}" class="btn btn-success col-12 ">
              <i class="fa fa-plus"></i> Nouveau tuteur
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
                                <th class="text-center">Email</th>
                                <th class="text-center">Téléphone</th>
                                <th class="text-center">Poste</th>
                                <th class="text-center">Heures(H)</th>
                                <th class="text-center">Paie(Francs CFA)</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Modifier</th>
                                <th class="text-center">Supprimer</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td class="text-center">{{ $teacher->created_at }}</td>
                                    <td class="text-center">{{ $teacher->name }}</td>
                                    <td class="text-center">{{ $teacher->email }}</td>
                                    <td class="text-center">{{ $teacher->phone }}</td>
                                    <td class="text-center">{{ $teacher->poste }}</td>
                                    <td class="text-center">{{ $teacher->total_hours  }} </td>
                                    <td class="text-center">{{ $teacher->amount }} </td>
                                    <td class="text-center">
                                        @if ($teacher->state == 1)
                                            <span class="badge bg-success text-white">Actif</span>
                                        @elseif($teacher->state == 0)
                                            <span class="badge bg-danger text-white">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.teacher.show', $teacher->id) }}" class="btn btn-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.teacher.destroy', $teacher->id) }}" method="POST">
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
