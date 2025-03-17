@extends('frontend.layout.app')


{{--@dd(auth()->user());--}}

@push('style')

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet"/>
@endpush

@section('content')

    <div class="container">


        <div class="ql-editor">
            {!! $insurance->text !!}
        </div>

    </div>

@endsection
