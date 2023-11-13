@extends('layouts.app')

@section('content')
    @if (session('error_message'))
        <div class="alert alert-danger text-center d-flex justify-content-between align-items-center">
            {{ session('error_message') }}
        </div>
    @endif

    @if (session('success_message'))
        <div class="alert alert-success text-center d-flex justify-content-between align-items-center">
            {{ session('success_message') }}
        </div>
    @endif


    <div class="page-header-title  mb-4">
        <div class="d-inline mx-auto">
            <h4>Profil de l'utilisateur</h4>
        </div>
    </div>


    <div class="page-body">

        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            <strong>Attention!</strong> Ici vous avez la possiblité de changer votre mot de passe.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Changer le mot de passe</h5>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('profile_update', $user->id) }}">
                            @csrf
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <label for="old_password" class="form-label">Ancien mot de passe</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password"
                                        placeholder="Ancien mot de passe">
                                    @error('old_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Nouveau mot de passe">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de
                                        passe</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirmer le mot de passe">
                                </div>
                            </div>

                            <div class="row justify-content-end mt-4">
                                <div class="col-sm-6 mx-auto">
                                    <button class="btn btn-primary" type="submit">Modifier</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Informations générales</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <label  class="form-label">Nom et prénoms</label>
                                <input type="text" class="form-control" value="{{$user->name}}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <label  class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{$user->email}}" disabled>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" disabled value="{{$user->phone}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
