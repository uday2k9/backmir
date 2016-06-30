@extends('admin/layout/admin_template')

@section('content')
 <a href="{!!url('admin/book/create')!!}" class="btn btn-success">Create Book</a>
 <hr>
 @if(Session::has('success'))
          <div class="alert-box success">
          <h2>{!! Session::get('success') !!}</h2>
          </div>
 @endif
 
 <table class="table table-striped table-bordered table-hover">
     <thead>
     <tr class="bg-info">
         <th>Id</th>
         <th>ISBN</th>
         <th>Title</th>
         <th>Author</th>
         <th>Publisher</th>
         <th>Thumbs</th>
         <th colspan="3">Actions</th>
     </tr>
     </thead>
     <tbody>
     @foreach ($books as $book)
         <tr>
             <td>{!! $book->id !!}</td>
             <td>{!! $book->isbn !!}</td>
             <td>{!! $book->title !!}</td>
             <td>{!! $book->author !!}</td>
             <td>{!! $book->publisher !!}</td>
            
             <td><img src="{!!asset('uploads/img/'.$book->image)!!}" height="35" width="30"></td>
             <td><a href="{!!url('admin/book',$book->id)!!}" class="btn btn-primary">Read</a></td>
             <td><a href="{!!route('admin.book.edit',$book->id)!!}" class="btn btn-warning">Update</a></td>
             <td>
             {!! Form::open(['method' => 'DELETE', 'route'=>['admin.book.destroy', $book->id]]) !!}
             {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
             {!! Form::close() !!}
             </td>
         </tr>
     @endforeach

     </tbody>

 </table>
 <div class="text-center"><?php echo $books->render(); ?></div>
@endsection
