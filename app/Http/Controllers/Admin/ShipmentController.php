<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\ShipmentPackage; /* Model name*/
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
use App\Helper\helpers;
class ShipmentController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function __construct() {

      view()->share('shipment_class','active');
    }
   public function index()
   {
      $limit = 10;
      $ShipmentPackage = ShipmentPackage::orderBy('name','ASC')->paginate($limit);
      //echo '<pre>';print_r($vitamins); exit;
      $ShipmentPackage->setPath('ShipmentPackage');
      return view('admin.shipmentpackage.index',compact('ShipmentPackage'),array('title'=>'Shipment Package Management','module_head'=>'Shipment Package'));

  }
  public function create()
  {

    $typesarray['LETTER']="LETTER";
    $typesarray['CARRIER_BOX_SMALL']="CARRIER_BOX_SMALL";
    $typesarray['CARRIER_BOX_MEDIUM']="CARRIER_BOX_MEDIUM";
    $typesarray['CARRIER_BOX_LARGE']="CARRIER_BOX_LARGE";
    $typesarray['CUSTOM']="CUSTOM";
    

    $parenttypesarray['0']="Select Parent Type";
    $parenttypesarray['1st Class']="1st Class";
    $parenttypesarray['Priority']="Priority";
    $dimensionarray['IN']="IN";
    $dimensionarray['FT']="FT";
    $dimensionarray['CM']="CM";
    $dimensionarray['M']="M";

    return view('admin.shipmentpackage.create',compact('typesarray','parenttypesarray','dimensionarray'),array('title'=>'Add Shipment Package','module_head'=>'Add Shipment Package'));
  }

  public function store(Request $request)
  {
      $obj = new helpers();
      $ShipmentPackage['name'] = Request::input('name');
      $ShipmentPackage['p_type'] = Request::input('p_type');
      $ShipmentPackage['type'] = Request::input('type');
      $ShipmentPackage['width'] = Request::input('width');
      $ShipmentPackage['height'] = Request::input('height');
      $ShipmentPackage['length'] = Request::input('length');
      
      $ShipmentPackage_row = ShipmentPackage::create($ShipmentPackage); 
      $lastinsertedId = $ShipmentPackage_row->id;
      Session::flash('success', 'Shipment Package added successfully'); 
      return redirect('admin/shipment-package');
  }
  public function edit($id){

    
    
    $parenttypesarray['0']="Select Parent Type";
    $parenttypesarray['1st Class']="1st Class";
    $parenttypesarray['Priority']="Priority";
    $dimensionarray['IN']="IN";
    $dimensionarray['FT']="FT";
    $dimensionarray['CM']="CM";
    $dimensionarray['M']="M";
     
    $ShipmentPackage = ShipmentPackage::where('id',$id)->first();

    if($ShipmentPackage->p_type=="Priority"){
    $typesarray['LETTER']="LETTER";
    $typesarray['CARRIER_BOX_SMALL']="CARRIER_BOX_SMALL";
    $typesarray['CARRIER_BOX_MEDIUM']="CARRIER_BOX_MEDIUM";
    $typesarray['CARRIER_BOX_LARGE']="CARRIER_BOX_LARGE";
    
    }
    if($ShipmentPackage->p_type=="1st Class"){
    $typesarray['CUSTOM']="CUSTOM";
    
    }


    return view('admin.shipmentpackage.edit',compact('ShipmentPackage','parenttypesarray','typesarray','dimensionarray'),array('title'=>'Edit Shipment Package','module_head'=>'Edit Shipment Package'));
      
    }
    public function update(Request $request, $id)
    { 

        $ShipmentPackageUpdate=Request::all();
        $ShipmentPackage=ShipmentPackage::where("id",$id)->first();
        $ShipmentPackage->update($ShipmentPackageUpdate);
        Session::flash('success', 'Shipment Package Updated successfully'); 
        return redirect('admin/shipment-package');
    }
    public function destroy($id)
    { 
        ShipmentPackage::find($id)->delete();
        Session::flash('success', 'Shipment Package deleted successfully'); 
        return redirect('admin/shipment-package');
    }

    
    
    
}
