@extends('admin/layout/ajax_template')
@section('content')

{!! Form::select('type',$typesarray,'',['class'=>'span8','id'=>'type']) !!}

@stop