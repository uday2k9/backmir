<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
use App\Model\Subscription;
use App\Model\Coupon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Hash;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;
use App\Helper\helpers;


class CouponController extends Controller {

    public function __construct() 
    {
        view()->share('coupon_class','active');
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
        $limit = 5;
        $coupons = DB::table('coupons')->orderBy('id','DESC')->paginate($limit);
        
        //$coupons->setPath('coupons');
        return view('admin.coupons.index',compact('coupons'),array('title'=>'Coupon Management','module_head'=>'Coupon'));

    }

    public function change_status()
    {   
        $coupon_id = Request::segment(3);
        $status = Request::segment(4);

        $coupon = Coupon::find($coupon_id);
        
        $coupon['status'] = $status;
        
        $coupon->save();

         Session::flash('success', 'Coupon status updated successfully'); 
         return redirect('admin/coupon');
    }

    public function destroy($id)
    {        
        Coupon::find($id)->delete();
       
        Session::flash('success', 'Coupon deleted successfully'); 
        return redirect('admin/coupon');
    }
    
    public function create()
    {
      return view('admin.coupons.create',array('title'=>'Coupon Management','module_head'=>'Add Coupon'));
    } 

    public function store(Request $request)
    {

        $name = Request::input('name');
        $code = Request::input('code');
        $type = Request::input('type');
        $discount = Request::input('discount');
       
         
        DB::table('coupons')->insert(
            ['name' => $name, 'code' => $code,'type' => $type,'discount' => $discount,'status'=>1]
        );
        Session::flash('success', 'Coupon added successfully'); 
        return redirect('admin/coupon');
    }

    public function checkCouponCode()
    {   
      $coupon_code = Request::input('coupon_code');
      $coupon_id = Request::input('coupon_id');
      if($coupon_id!=""){
        echo $cnt = Coupon::where("code",$coupon_code)->where("id",'!=',$coupon_id)->count();
      }
      else
        echo $cnt = Coupon::where("code",$coupon_code)->count();
    }

   
    public function edit($id)
    {
        $coupons=DB::table('coupons')->where("id",$id)->first();
        return view('admin.coupons.edit',compact('coupons'),array('title'=>'Edit Coupon','module_head'=>'Edit Coupon'));
    }


    public function update(Request $request, $id)
    {
        $obj = new helpers();
        $subUpdate=Request::all();
       unset($subUpdate['_method']);
      unset($subUpdate['_token']);

      Coupon::where('id', '=', $id)->update($subUpdate);
      Session::flash('success', 'coupon updated successfully'); 
      return redirect('admin/coupon');
    }

    
}