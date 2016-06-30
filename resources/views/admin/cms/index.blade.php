@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <!-- <a href="{!!url('admin/cms/create')!!}" class="btn btn-success pull-right">Create Content</a> -->
 <hr>
 
   <div class="module">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Page Name</th>
                                            <th>Meta Title</th>
                                            <th>Meta Keywords</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=1;
                                        //print_r($cms); exit;?>
                                        @foreach ($cms as $each_cms)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">

                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $each_cms->title !!}
                                            </td>
                                            <td class=" ">
                                                {!! $each_cms->meta_name !!}
                                            </td>
                                            <td class=" ">
                                                {!! $each_cms->meta_keyword !!}
                                            </td>
                                            
                                            <td>
                                                <a href="{!!route('admin.cms.edit',$each_cms->id)!!}" class="btn btn-warning">Edit</a>
                                            </td>
                                            
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $cms->render(); ?></div>
@endsection
