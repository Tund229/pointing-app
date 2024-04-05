@extends('layouts.app')

@section('content')
    <hr>
    <div class="page-header-title  mb-4">
        <div class="d-inline text-center text-primary">
            <h4>Payer les employes fixes et générer les fiches de paie</h4>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('admin.paiement.employes.store') }}">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6 mb-4">
                                <label class="control-label">Nom de l'employes</label>
                                <select class="form-control select2" name="user_id">
                                    <option value="">Sélectionnez un employé</option>
                                    @foreach ($employes as $employe)
                                        <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 mb-4">
                                <label class="control-label">Mois</label>
                                <select class="form-control select2" name="month">
                                    <option value="">Sélectionnez un mois</option>
                                    <option value="Janvier">Janvier</option>
                                    <option value="Février">Février</option>
                                    <option value="Mars">Mars</option>
                                    <option value="Avril">Avril</option>
                                    <option value="Mai">Mai</option>
                                    <option value="Juin">Juin</option>
                                    <option value="Juillet">Juillet</option>
                                    <option value="Août">Août</option>
                                    <option value="Septembre">Septembre</option>
                                    <option value="Octobre">Octobre</option>
                                    <option value="Novembre">Novembre</option>
                                    <option value="Décembre">Décembre</option>
                                </select>
                                @error('month')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-left" type="submit">Payer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
