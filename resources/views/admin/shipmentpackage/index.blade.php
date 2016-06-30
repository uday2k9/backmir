@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 


 <a href="{!!url('admin/shipment-package/create')!!}" class="btn btn-success pull-right">Create Shipment Package</a>
    <hr> 
   <div class="module">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Name</th>
                                            <th>Parent Types</th>
                                            <th>Types</th>
                                            <th>Width</th>
                                            <th>height</th>
                                            <th>length</th>
                                            <th>Dimension Units</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                           
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=1;
                                        //print_r($cms); exit;?>
                                        @foreach ($ShipmentPackage as $shipmentpackage)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">

                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->name !!}
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->p_type !!}
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->type !!}
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->width !!}
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->height !!}
                                            </td>
                                            <td class=" ">
                                                {!! $shipmentpackage->length !!}
                                            </td>

                                            <td class=" ">
                                                {!! $shipmentpackage->dimension_units !!}
                                            </td>

                                            <td>
                                                <a href="{!!route('admin.shipment-package.edit',$shipmentpackage->id)!!}" class="btn btn-warning">Edit</a>
                                            </td>
                                            <td>
                                                {!! Form::open(['method' => 'DELETE', 'route'=>['admin.shipment-package.destroy', $shipmentpackage->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                           
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $ShipmentPackage->render(); ?></div>
@endsection
