@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 
 <hr>
 
   <div class="module">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Name</th>
                                            <th>Display Name</th>
                                            <th>Value</th>
                                            <th>Edit</th>
                                           
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=1;
                                        //print_r($cms); exit;?>
                                        @foreach ($sitesettings as $sitesetting)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">

                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $sitesetting->name !!}
                                            </td>

                                            <td class=" ">
                                                {!! $sitesetting->display_name !!}
                                            </td>

                                            <td class=" ">
                                                {!! strip_tags($sitesetting->value) !!}
                                            </td>

                                            <td>
                                                <a href="{!!route('admin.sitesetting.edit',$sitesetting->id)!!}" class="btn btn-warning">Edit</a>
                                            </td>
                                           <!-- <td>
                                                {!! Form::open(['method' => 'DELETE', 'route'=>['admin.sitesetting.destroy', $sitesetting->id]]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            </td>-->
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $sitesettings->render(); ?></div>
@endsection
