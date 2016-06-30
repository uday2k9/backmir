<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Faq; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;

class FaqController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() 
    {
        view()->share('faq_class','active');
    }
   public function index()
   {
        $limit = 10;
		$faq = DB::table('faqs')->orderBy('id','DESC')->paginate($limit);
        //echo '<pre>';print_r($vitamins); exit;
	    $faq->setPath('faq');
        return view('admin.faq.index',compact('faq'),array('title'=>'FAQ Management','module_head'=>'FAQ'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faq.create',array('title'=>'FAQ Management','module_head'=>'Add FAQ'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faq=Request::all();
        $question = Request::input('question');
        $answer=htmlentities(Request::input('answer'));
        $updated_at=Request::input('updated_at');
        $created_at=Request::input('created_at');
        
        //print_r($faq); exit;
        //Faq::create($faq);
        DB::table('faqs')->insert(
            ['question' => $question, 'answer' => $answer,'updated_at' => $updated_at,'created_at' => $created_at]
        );
        Session::flash('success', 'FAQ added successfully'); 
        return redirect('admin/faq');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $faq=Faq::find($id);
       return view('admin.faq.show',compact('faq'));
    }

    public function edit($id)
    {
        $faq=Faq::find($id);
        return view('admin.faq.edit',compact('faq'),array('title'=>'Edit FAQ','module_head'=>'Edit FAQ'));
    }

   
    public function update(Request $request, $id)
    {
        //
           $faqUpdate=Request::all();
           $faq=Faq::find($id);
           $faqUpdate['answer']=htmlentities($faqUpdate['answer']);
           $faq->update($faqUpdate);
           Session::flash('success', 'FAQ updated successfully'); 
           return redirect('admin/faq');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    public function destroy($id)
    {
        //
        Faq::find($id)->delete();
        Session::flash('success', 'FAQ deleted successfully'); 
        return redirect('admin/faq');
    }
    
    /* FOR FRONT END  FAQ SHOWING */
	
	public function faqList()
    {
        //ehco "ee"; exit;
       $allfaq =  DB::table('faqs')->get();
       return view('frontend.faq.faq',compact('allfaq'),array('title'=>'Miramix FAQ','module_head'=>'Miramix FAQ'));
    }
}
