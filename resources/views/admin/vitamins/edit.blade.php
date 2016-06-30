@extends('admin/layout/admin_template')

@section('content')
    
    {!! Form::model($vitamin,['method' => 'PATCH','route'=>['admin.vitamin.update',$vitamin->id]]) !!}
   
    <div class="control-group">
        <label class="control-label" for="basicinput">Name</label>
        <div class="controls">
            {!! Form::text('name',null,['class'=>'span8']) !!}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="basicinput">Weight</label>
        <div class="controls">
            {!! Form::text('weight',null,['class'=>'span8']) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@stop