@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <a href="{!!url('admin/vitamin/create')!!}" class="btn btn-success pull-right">Create Vitamins</a>
 <hr>
 
   <div class="module">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Name</th>
                                            <th>Weight</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=1;?>
                                        @foreach ($vitamins as $vitamin)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">

                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $vitamin->name !!}
                                            </td>
                                            <td class=" ">
                                                {!! $vitamin->weight !!}
                                            </td>
                                            <td>
                                                <a href="{!!route('admin.vitamin.edit',$vitamin->id)!!}" class="btn btn-warning">Edit</a>
                                            </td>
                                            <td>
                                                {!! Form::open(['method' => 'DELETE', 'route'=>['admin.vitamin.destroy', $vitamin->id]]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $vitamins->render(); ?></div>
@endsection
