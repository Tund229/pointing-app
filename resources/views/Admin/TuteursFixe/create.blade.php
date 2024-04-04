@extends('layouts.app')

@section('content')
    <hr>
    <div class="page-header-title  mb-4">
        <div class="d-inline text-center text-primary">
            <h4>Ajouter un nouveau tuteur fixe </h4>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('admin.tuteurs-fixe.store') }}">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6 mb-4">
                                <label class="control-label">Nom et Prénoms</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 mb-4">
                                <label class="control-label">Téléphone</label>
                                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-4 mb-4">
                                <label class="control-label">Montant</label>
                                <input type="number" class="form-control" name="amount" value="{{ old('amount') }}">
                                @error('amount')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4 mb-4">
                                <label class="control-label">Poste</label>
                                <input type="text" class="form-control" name="poste"
                                    value="{{ old('poste', 'Enseignant') }}">
                                @error('poste')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4 mb-4">
                                <label class="control-label">Reseau</label>
                                <select name="reseau" id="reseau" class="form-control shadow-none">
                                    <option value="">Sélectionnez un réseau</option>
                                    @foreach ($cashInServices as $service)
                                        @if ($service['serviceCode'] !== 'FM_SN_CASHIN' && $service['serviceCode'] !== 'WIZALL_SN_CASHIN')
                                            <option value="{{ $service['serviceCode'] }}"
                                                {{ old('reseau') == $service['serviceCode'] ? 'selected' : '' }}>
                                                {{ str_ireplace('cashin', '', $service['serviceName']) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('reseau')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-left" type="submit">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
