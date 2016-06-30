<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brandmember;          /* Model name*/
use App\Model\Product;              /* Model name*/
use App\Model\ProductIngredientGroup;    /* Model name*/
use App\Model\ProductIngredient;      /* Model name*/
use App\Model\ProductFormfactor;      /* Model name*/
use App\Model\Ingredient;             /* Model name*/
use App\Model\FormFactor;             /* Model name*/
use App\Model\Order;             /* Model name*/
use App\Model\OrderItem;             /* Model name*/
use App\Model\AddProcessOrderLabel; /* Model name*/
use App\Model\ShipmentPackage; /* Model name*/
use App\Model\OrderPostmasterShipment;   /* Model name*/

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

use App\libraries\Postmaster;
class AjaxController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    public function create_child_types()
    {
        $p_type=Request::input('parent_type');
        //print_r($p_type);
        if($p_type=="Priority"){
                $typesarray['LETTER']="LETTER";
                $typesarray['CARRIER_BOX_SMALL']="CARRIER_BOX_SMALL";
                $typesarray['CARRIER_BOX_MEDIUM']="CARRIER_BOX_MEDIUM";
                $typesarray['CARRIER_BOX_LARGE']="CARRIER_BOX_LARGE";
                
        }
        if($p_type=="1st Class"){
                
                $typesarray['CUSTOM']="CUSTOM";
        }

        return view('admin.ajax.create_child_types',compact('typesarray'));

        
    }
    public function create_selected_order_counter()
    {

        $AddProcessOrderLabel=AddProcessOrderLabel::all();
       
        
        $OrderId="";
        foreach ($AddProcessOrderLabel as $key => $value) {
            if($OrderId==""){
                $OrderId=$value->order_id;
            }else{
                $OrderId=$OrderId.",".$value->order_id;
            }
        }
        $Order = Order::with('getOrderMembers')->whereRaw('id IN('.$OrderId.')')->get();
                

        

        return view('admin.ajax.create_selected_order_counter',compact('AddProcessOrderLabel','Order'));
    }
    public function getshipmentpackage(){
        //echo "hallo";
        $ptype=Request::input('ptype');
        $settr=Request::input('settr');
        $ShipmentPackage=ShipmentPackage::where("p_type",$ptype)->get();
        return view('admin.ajax.getshipmentpackage',compact('ShipmentPackage','settr'));

    }
    public function storetopostmasterqueue(){
        $idsx=Request::input('oid');
        OrderPostmasterShipment::where('order_id', '=', $idsx)->delete();
                
        $OrderPostmasterShipment['order_id'] = Request::input('oid');
        $OrderPostmasterShipment['shipment_package_id'] = Request::input('pak');
        $OrderPostmasterShipment['weight'] = Request::input('wat');
        $OrderPostmasterShipment['weight_unit'] = "LB";
        if(OrderPostmasterShipment::create($OrderPostmasterShipment)){
            echo "1";
        } 


    }
    public function generatelabel(){

        $postmaster_api_key = env("POSTMASTER_API_KEY");


        if($postmaster_api_key != "")
        {

            $OrderPostmasterShipment=OrderPostmasterShipment::all();
            $postmasterarray=array();
            foreach ($OrderPostmasterShipment as $value) {


               
                $order_id=$value->order_id;
                $package_id=$value->shipment_package_id;
                $Order = Order::where("id",$order_id)->first();
                $ShipmentPackage = ShipmentPackage::where("id",$package_id)->first();

                $serialize_address = unserialize($Order->shiping_address_serialize);
                //$postmasterarray['to']['company']="";$serialize_address['first_name'].' '.$serialize_address['last_name'];
                $postmasterarray['to']['company']="";
                $postmasterarray['to']['contact']=$serialize_address['first_name'].' '.$serialize_address['last_name'];
                $postmasterarray['to']['line1']=$serialize_address['address'];
                $postmasterarray['to']['line2']=$serialize_address['address2'];
                $postmasterarray['to']['city']=$serialize_address['city'];
                $postmasterarray['to']['state']=$serialize_address['zone_id'];
                $postmasterarray['to']['zip_code']=$serialize_address['postcode'];
                $postmasterarray['to']['phone_no']=$serialize_address['phone'];

                $postmasterarray['from']['company']="MIRAMIX";
                $postmasterarray['from']['contact']="";
                $postmasterarray['from']['line1']="51 jones Falls Terrace";
                $postmasterarray['from']['line2']="";
                $postmasterarray['from']['city']="Baltimore";
                $postmasterarray['from']['state']="MD";
                $postmasterarray['from']['zip_code']="21209";
                $postmasterarray['from']['phone_no']="8125080674";

                $postmasterarray['type'] =$ShipmentPackage->type;
                $postmasterarray['carrier'] = "USPS";


                if(isset($ShipmentPackage->p_type) && $ShipmentPackage->p_type == "Priority")
                    $postmasterarray['service'] = "3DAY";
                else
                    $postmasterarray['service'] = "2DAY";

                $postmasterarray['package']['weight_units']="OZ";
                $postmasterarray['package']['weight']=$value->weight;
                $postmasterarray['package']['length']=$ShipmentPackage->length;
                $postmasterarray['package']['width']=$ShipmentPackage->width;
                $postmasterarray['package']['height']=$ShipmentPackage->height;
                $postmasterarray['label']['type']="NORMAL";
                $postmasterarray['label']['format']="PDF";
                $postmasterarray['label']['size']="MEDIUM";
                //Postmaster::setApiKey("tt_MTUzOTEwMDE6RFp4V3ZybTB3bHRabm9ocENqaVlpUlZqcVRv");
                //Postmaster::setApiKey("pp_MTUzOTEwMDE6TFRVTDJSVVF1RWJQRk03S3JndGVtdGt0U2hz");
                Postmaster::setApiKey($postmaster_api_key);
                
                $result = Postmaster::create($postmasterarray);
                
                $trac_id=$result['tracking'][0];
                $lab=$result['packages'][0]['label_url'];


                $destination='./uploads/pdf/'.$Order->order_number.".pdf";
                $source='https://www.postmaster.io/'.$lab;
                $filenamex=$Order->order_number.".pdf";
                 $data = file_get_contents($source);
                 $file = fopen($destination, "w+");
                 fputs($file, $data);
                 fclose($file);
                 $Order->order_number.".pdf";
                 Order::where('id', $order_id)->update(['tracking_number' => $trac_id,'shipping_carrier'=>'USPS','usps_label'=>$filenamex,'order_status'=>'prepare shippment']);


            }
            DB::table('add_process_order_labels')->delete();
            DB::table('order_postmaster_shipment')->delete();
            echo "Done";

        }
    }
}
