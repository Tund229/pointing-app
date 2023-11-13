@extends('layouts.app')

@section('content')
    <hr>
    <div class="page-header-title  mb-4">
        <div class="d-inline text-center text-primary">
            <h4>Modifier une reclamation</h4>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('teacher.reclamations.update', $reclamation->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6  mb-4">
                                <label class="control-label">Titre</label>
                                <input type="text" class="form-control" name="title" value="{{ $reclamation->title }}">
                                @error('title')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6  mb-4">
                                <label class="control-label">Description</label>
                                <textarea name="description" class="form-control" id="" 
                                    > {{ $reclamation->description }}</textarea>
                                @error('description')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-left" type="submit">Modifier</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
