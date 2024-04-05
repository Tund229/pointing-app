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

        <h4 class="m-4 text-center text-danger">Veuillez entrer votre code</h4>
        <p class="text-center">Entrez le code que vous avez envoyé à l'administrateur dans le champ ci-dessous pour accéder à
            la page de paiement.</p>

        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('admin.verification.check') }}">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6  mb-4 mx-auto ">
                                <label class="control-label">Code</label>
                                <input type="text" name="code" class="form-control"
                                    placeholder="Entrez votre code ici" required>
                                @error('name')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-sm-6 mx-auto">
                                <button class="btn btn-primary pull-left" type="submit">Accéder</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
