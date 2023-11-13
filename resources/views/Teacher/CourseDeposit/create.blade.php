@extends('layouts.app')

@section('content')
    <hr>
    <div class="page-header-title  mb-4">
        <div class="d-inline text-center text-primary">
            <h4>Déposer un nouveau cours </h4>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST" action="{{ route('teacher.course-deposit.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6  mb-4">
                                <label for="course">Matières</label>
                                <select name="course_id" id="course" class="form-control">
                                    <option value="" disabled selected>--Sélectionner une matière--</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="promotion">Promotion</label>
                                <select name="promotion_id" id="promotion" class="form-control">
                                    <option value="" disabled selected>--Sélectionner une promotion--</option>
                                    @foreach ($promotions as $promotion)
                                        <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                                    @endforeach
                                </select>
                                @error('promotion_id')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-sm-6 mb-4">
                                <label class="control-label">Support de fichier</label>
                                <input type="file" class="form-control" name="support_file">
                                @error('support_file')
                                    <div class="text-danger text-center f-w-400">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="col-sm-6 ">
                                <label for="comment">Vos indications pour qu'on puisse savoir comment gérer le
                                    document</label>
                                <textarea name="comment" id="comment" class="form-control"></textarea>
                                @error('comment')
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
