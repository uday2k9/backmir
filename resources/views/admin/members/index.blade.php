@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('success') !!}</strong>
    </div>
 @endif
 @if(Session::has('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}</strong>
    </div>
 @endif
 
    <div class="module">
             
       <form method="post" id="filterform" action="<?php echo url();?>/admin/member">
          <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
         <div class="pull-left">
            <div class="search_top"><input type="botton" onclick="location.href='<?php echo url()."/admin/add-member"?>'" class="btn btn-success marge add_memeber_btn" value="Add Member" name="addmember"/></div>
         </div>
            <div class="filter filt_css pull-right"><span>Search members</span> <input type="text" name="searchstring" value="<?php echo $searchstring?>" id="searchstring" />
            <div class="search_top"><input type="submit" class="btn btn-success marge" value="search" name="search"/></div>
            </div>
                
        </form>
            
        <table cellpadding="0" id="member_admin" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>
                    <th style="width:20px;">Sl No.</th>
                    <th>Name</th>
                    <th style="width:124px;">Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Admin Status</th>
                    <th style="width:44px;">Edit</th>
                    <th style="width:64px;">Delete</th>
                </tr>
            </thead>
                
                
            <tbody>
                <?php $i=1;?>
                @foreach ($members as $member)
                <tr class="odd gradeX">
                    <td class=""><?php echo $i; ?></td>
                    <td class="">{!! $member->fname.' '.$member->lname !!}</td>
                    <td class="">{!! $member->email !!}</td>
                    <td class="">{!! $member->phone_no !!}</td>
                    <td class="">{!! $member->username !!}</td>
                    <td class="">
                        @if ($member->status == 1)
                            Active 
                        @else
                            <a href="{{ URL::to('admin/member/status/' . $member->id) }}" data-toggle="tooltip" title="Make Active" >Inactive</a>
                        @endif
                    </td>
                    <td class="">
                        @if ($member->admin_status == 1)
                           <a href="{{ URL::to('admin/member/admin_inactive_status/' . $member->id) }}" data-toggle="tooltip" title="Make Inactive" >Active</a>
                        @else
                           <a href="{{ URL::to('admin/member/admin_active_status/' . $member->id) }}" data-toggle="tooltip" title="Make Active" >Inactive</a>
                        @endif
                    </td>
                   
                   <!--  <td><a href="{!!route('admin.member.edit',$member->id)!!}" class="btn btn-warning">Edit</a></td> -->
                    <td>
                        <a href="{!!route('admin.member.edit',$member->id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.member.destroy', $member->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php echo $members->render(); ?></div>
<script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>
@endsection
