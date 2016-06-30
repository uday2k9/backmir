@extends('layout/template')

@section('content')
    <h1>Create Book</h1>
    {!! Form::open(['url' => 'image','method'=>'POST', 'files'=>true]) !!}
   
    <div class="form-group">
        {!! Form::label('Image', 'Image:') !!}
        {!! Form::file('image',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
@stop