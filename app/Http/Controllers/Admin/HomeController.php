<?php namespace App\Http\Controllers\Admin;

use App\Book;
use App\User;
use App\Model\ProductFormfactorDuration;     /* Model name: For custom time frame */
use App\Model\ProductFormfactor;     /* Model name: For custom time frame */
use App\Model\Searchtag;     /* Model name: For custom time frame */

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;

class HomeController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
  public function __construct() {

      view()->share('home_class','active');
    }
  public function index()
  {
    return view('admin.home.index',array('title' => 'Dashboard','module_head'=>'Dashboard'));
  }

  public function populate()
  {
    
    //$product_formfactors = ProductFormfactor::all();

    

    $product_formfactors = DB::table('product_formfactors as pff')
      ->select(DB::raw('pff.*,ff.price, pi.ingredient_price'))
      ->leftJoin('form_factors as ff','ff.id','=','pff.formfactor_id')
      ->leftJoin('product_ingredients as pi','pi.product_id','=','pff.product_id')
      ->orderBy('pff.product_id', 'asc')
      ->get();     

    //$product_formfactors = ProductFormfactor::all()->select('firstname', 'lastname')->get();
    //echo "<pre />";

    
    //print_r($product_formfactors);
    //exit;

    foreach ($product_formfactors as $product_formfactor) {
      //$product_formfactor-> ($product_formfactor->id)."<br>";

      //print_r($product_formfactor);

      $days = array(30, 90);

      for($i=0; $i < count($days); $i++)
      {

        $pffd = new ProductFormfactorDuration;

        $form_fee = $product_formfactor->price;
        $servings = $product_formfactor->servings;
        $ingredient_price =  $product_formfactor->ingredient_price;
        $actual_price_multiplier =  $product_formfactor->actual_price;

        if(!isset($ingredient_price) || $ingredient_price == "")
          $ingredient_price = 0;


        $min_price = (($form_fee * $servings) + $ingredient_price) * $days[$i];
        $recommended_price = $min_price * 4;
        $actual_price = $actual_price_multiplier * $days[$i];

        $pffd->product_formfactor_id  = $product_formfactor->id;
        $pffd->duration               = $days[$i];
        //$pffd->min_price              = $min_price;
        //$pffd->recommended_price      = $recommended_price;
        $pffd->min_price              = 0;
        $pffd->recommended_price      = 0;
        
        $pffd->actual_price           = $actual_price;
        

        $pffd->save();

      }

    }
    return view('admin.home.index',array('title' => 'Dashboard','module_head'=>'Dashboard', 'msg' => 'Product prices successfully populated.'));
    
    
  }


  public function populate_searchtags()
  {
    
    //$product_formfactors = ProductFormfactor::all();

    

    $brandmembers = DB::table('brandmembers as bm')
      
      ->where('role', 1)     
      ->get();     

    //$product_formfactors = ProductFormfactor::all()->select('firstname', 'lastname')->get();
    //echo "<pre />";

    
    //print_r($product_formfactors);
    //exit;

    foreach ($brandmembers as $brandmember) {
      //$product_formfactor-> ($product_formfactor->id)."<br>";

     
      // `product_id`, `name`, `popularity`, `type`


      $bm_id = $brandmember->id;
      $business_name = $brandmember->business_name;

      $products = DB::table('products as pr')
      ->select(DB::raw('id'))      
      ->where('brandmember_id', $bm_id)     
      ->get();


      foreach ($products as $product) {
          echo $business_name." for product id ".$product->id." inserted... <br>";

          $st = new Searchtag;
      
          $st->product_id  = $product->id;
          $st->name  = $business_name;
          $st->popularity  = 0;
          $st->type  = "brand_name";
          
          $st->save();

      }
      


     
      

      

    }
    exit;
    //return view('admin.home.index',array('title' => 'Dashboard','module_head'=>'Dashboard'));
  }



  // For Admin Edit Profile
  public function getProfile()
  {
    
    $user=User::find(1);
      // return view('admin.books.edit',compact('book'));
    return view('admin.home.edit_profile',compact('user'));
  }
  
  public function show($id)
  {
     $user=User::find(1);
    return view('admin.home.edit_profile',compact('user'));
  }


  

  public function update(Request $request, $id)
  {
  
    $userUpdate=Request::all();
    $user=User::find($id); 

    if (Input::hasFile('image'))
    {
      $destinationPath = 'uploads/admin_profile/'; // upload path
      $thumb_path = 'uploads/admin_profile/thumb/';
      $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
      $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
      Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
      // $this->create_thumbnail($thumb_path,$fileName,$extension); 
      $user['admin_icon']=$fileName;

      // unlink old photo
      @unlink('uploads/admin_profile/'.Request::input('admin_icon'));
    }
    else
       $user['admin_icon']=Request::input('admin_icon');


     $user->update($userUpdate);
     Session::flash('success', 'Your profile updated successfully.'); 
     return redirect('admin/admin-profile');
  }

  public function forgotPassword()
    {
      return view('admin.home.forgotpassword');
    }

  public function forgotpasswordcheck()
    {
        if(Request::isMethod('post'))
        {
          $email = Request::input('email');
          $users = DB::table('users')->where('email', '=', $email)->get();
          $random_code = mt_rand();
          $updateWithCode = DB::table('users')->where('email', '=', $email)->update(array('code_number' => $random_code));
          $sitesettings = DB::table('sitesettings')->get();
          //exit;
          if(!empty($sitesettings))
          {
            foreach($sitesettings as $each_sitesetting)
            {
              if($each_sitesetting->name == 'email')
              {
                $admin_users_email = $each_sitesetting->value;
              }
            }
          }

          if(!(empty($users)))
          {
            $user_name = $users[0]->name;
            $user_email = $users[0]->email;
            $resetpassword_link = url().'/admin/resetpassword/'.base64_encode($user_email).'-'.base64_encode($random_code);
            //echo $resetpassword_link; exit;
            $sent = Mail::send('admin.home.reset_password_link', array('name'=>$user_name,'email'=>$user_email,'reset_password_link'=>$resetpassword_link), 
            function($message) use ($admin_users_email, $user_email,$user_name)
            {
                $message->from($admin_users_email);
                $message->to($user_email, $user_name)->subject('Forgot Password Email!');
            });

            if( ! $sent) 
            {
              Session::flash('error', 'something went wrong!! Mail not sent.'); 
              return redirect('admin/forgotpassword');
            }
            else
            {
              Session::flash('success', 'Please check your email to reset your password.'); 
              return redirect('auth/login');
            }              
          }
          else
          {
              Session::flash('error', 'Email Id not matched.'); 
              return redirect('admin/forgotpassword');
          }
          
        }
        
    }
  
   public function resetpassword($email)
    {
      //$user_email = base64_decode($email);
      //echo "h= ".$user_email; exit;
      return view('admin.home.resetpassword',array('title'=>'Reset Password','admin_email'=>$email));
    }
  
 public function updatePassword($email)
    {
      $user_email = base64_decode($email);
      $password = Request::input('password');
      //echo "h= ".$user_email; exit;Hash::make()
      DB::table('users')
            ->where('email', $user_email)
            ->update(['password' => Hash::make($password)]);

      Session::flash('success', 'Password successfully changed.'); 
      //return view('admin.home.resetpassword');
      return redirect('admin/resetpassword/'.$email);
    }

  public function changePass()
  {
    if(Request::isMethod('post'))
    {
      $old_password = Request::input('old_password');
      

      $password = Request::input('password');
      $conf_pass = Request::input('conf_pass');

      // Get Admin's password
      $user=User::find(Auth::id());

      if(Hash::check($old_password, $user['password']))
      {
        if($password!=$conf_pass){
          Session::flash('error', 'Password and confirm password is not matched.'); 
          return redirect('admin/change-password');

        }
        else{
          DB::table('users')->where('id', Auth::id())->update(array('password' => Hash::make($password)));
          
          Session::flash('success', 'Password successfully changed.'); 
          return redirect('admin/change-password');
        }
      }
      else{
        Session::flash('error', 'Old Password does not match.'); 
        return redirect('admin/change-password');
      }
    }

    return view('admin.home.changepassword',array('title' => 'Change Password','module_head'=>'Change Password'));
  }

}
