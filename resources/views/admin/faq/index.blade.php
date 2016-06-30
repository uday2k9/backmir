@extends('admin/layout/admin_template')
 
@section('content')

  <?php //echo $faq_class;  exit; ?>
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <a href="{!!url('admin/faq/create')!!}" class="btn btn-success pull-right">Create Content</a>
 <hr>
 
   <div class="module">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Question</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($faq as $each_faq)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">
                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $each_faq->question !!}
                                            </td>
                                            
                                            <td>
                                                <a href="{!!route('admin.faq.edit',$each_faq->id)!!}" class="btn btn-warning">Edit</a>
                                            </td>
                                            <td>
                                                {!! Form::open(['method' => 'DELETE', 'route'=>['admin.faq.destroy', $each_faq->id]]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $faq->render(); ?></div>
@endsection
