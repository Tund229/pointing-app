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



        <h3 class="m-4 text-center text-primary">Liste des administrateurs</h3>
        <div class="col-12 col-lg-3 my-4">
          <a href="{{route('admin.admin.create')}}" class="btn btn-success col-12 ">
              <i class="fa fa-plus"></i> Nouvel admin
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
                                <th class="text-center">Status</th>
                                <th class="text-center">Modifier</th>
                                <th class="text-center">Supprimer</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td class="text-center">{{ $admin->created_at }}</td>
                                    <td class="text-center">{{ $admin->name }}</td>
                                    <td class="text-center">{{ $admin->email }}</td>
                                    <td class="text-center">{{ $admin->phone }}</td>
                                    <td class="text-center">{{ $admin->poste ?? "-" }} </td>
                                    <td class="text-center">
                                        @if ($admin->state == 1)
                                            <span class="badge bg-success text-white">Actif</span>
                                        @elseif($admin->state == 0)
                                            <span class="badge bg-danger text-white">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.admin.show', $admin->id) }}" class="btn btn-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.admin.destroy', $admin->id) }}" method="POST">
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
                    title: 'Supprimez cet admin ?',
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
