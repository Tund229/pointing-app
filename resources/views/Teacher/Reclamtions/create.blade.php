@extends('layouts.app')

@section('content')
    <hr>
    <div class="page-header-title  mb-4">
        <div class="d-inline text-center text-primary">
            <h4>Ajouter une reclamation</h4>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('teacher.reclamations.store') }}">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6  mb-4">
                                <label class="control-label">Titre</label>
                                <input type="text" class="form-control" name="title">
                                @error('title')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6  mb-4">
                                <label class="control-label">Description</label>
                                <textarea name="description" class="form-control"  id=""></textarea>
                                @error('description')
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
