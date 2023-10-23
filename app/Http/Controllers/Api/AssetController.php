<?php
namespace App\Http\Controllers\Api;

use App\Models\Asset;
use App\Models\Track;
use App\Models\Vehicle;
use App\Models\Furniture;
use App\Models\Electronic;
use App\Models\IssuedAsset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    public function index()
    {
       $assets=Asset::get();
       $computer = Asset::where('subcategory','Computer')->count();
       $phone = Asset::where('subcategory','Phone')->count();
       $tv = Asset::where('subcategory','Television')->count();
       $disk = Asset::where('subcategory','Disk')->count();
           return response()->json(['success'=>true,'status'=>300, 'data'=> $assets,'computer'=> $computer,'phone'=> $phone,
           'tv'=> $tv,'disk'=> $disk,
        ],200); 
    }
        public function assetsByCategory()
        {
    $assets=Asset::where('status','!=','DISPOSED')->where('control',1)->get();
    $countsByCategory = $assets->groupBy('category')->map->count();
    return response()->json( $countsByCategory,200);
        }

        public function status()
{
    $statuses = Asset::groupBy('status')->where('status','!=','DISPOSED')->where('control',1)
    ->selectRaw('count(*) as count, status')
    ->get();
$chartData = [];
foreach ($statuses as $status) {
    $chartData[] = [
        'name' => $status->status,
        'count' => $status->count
    ];
}
return response()->json($chartData);
}
    public function faults()
    {
        $electronics=Maintainance::where('flug',1)->get();
        $faultsCount = Maintainance::where('flug',1)->count();
        return response()->json(['success'=>true,'status'=>300, 'data'=> $electronics,'count'=>$faultsCount ],200);
    }

    public function electronics()
{
    $electronics=Electronic::with('asset.staff')->with('asset.section')->with('asset.office')->get();
    $laptop=Electronic::where('computer_type','Laptop')->count();
    $lap=Electronic::where('computer_type', 'Laptop')
    ->with(['asset.staff', 'asset.section', 'asset.office'])
    ->get();
    $desktop=Electronic::where('computer_type','Desktop')->count();
    $mobilephone=Electronic::where('phone_type','MobilePhone')->count();
    $landline=Electronic::where('phone_type','Landline')->count();
    return response()->json(['success'=>true,'status'=>300, 'data'=> $electronics ,'laptop'=>$laptop,'desktop'=>$desktop,
    'mobilephone'=>$mobilephone,'landline'=>$landline, 'lap'=>$lap],200);
}

public function laptops()
{
    $laptops = Electronic::where('computer_type', 'Laptop')
        ->with(['asset.staff', 'asset.section', 'asset.office'])
        ->get();
        
    return response()->json([
        'success' => true,
        'status' => 300,
        'data' => $laptops,
    ], 200);
}

public function desktops()
{
    $desktops = Electronic::where('computer_type', 'Desktop')
        ->with(['asset.staff', 'asset.section', 'asset.office'])
        ->get();
    return response()->json([
        'success' => true,
        'status' => 300,
        'data' => $desktops,
    ], 200);
}
public function televisions()
{
    $televisions = Electronic::whereHas('asset', function ($query) {
        $query->where('subcategory', 'Television');
    })->with(['asset.staff', 'asset.section', 'asset.office'])->get();

    return response()->json([
        'success' => true,
        'status' => 300,
        'data' => $televisions,
    ], 200);
}

public function disks()
{
    $disks = Electronic::whereHas('asset', function ($query) {
        $query->where('subcategory', 'Disk');
    })->with(['asset.staff', 'asset.section', 'asset.office'])->get();
    return response()->json([
        'success' => true,
        'status' => 300,
        'data' => $disks,
    ], 200);
}




public function furniture()
{
    $furniture = Furniture::with(['asset.office'])->get();
    return response()->json(['success'=>true,'status'=>300, 'data'=> $furniture ],200);
}



public function store(Request $request)
{  
        $assets = new Asset();
        $assets->category = $request->category;
        $assets->user_id = $request->user_id;
        $assets->office_id = $request->office_id;
        $assets->section_id = $request->section_id;
        $assets->staff_id = $request->staff_id;
        $assets->condition = $request->condition;
        $assets->subcategory = $request->subcategory;
        $assets->asset_tag = $request->asset_tag;
        $assets->purchase_date = $request->purchase_date;
        $assets->purchasing_price = $request->purchasing_price;
        $assets->save();
    
    if ($request->category === 'Electronic') {
        $electronic=new Electronic();
        $electronic->asset_id=$assets->id;
        $electronic->chapa=$request->chapa;
        $electronic->modeli=$request->modeli;
        $electronic->chapa=$request->chapa;
        $electronic->serial_no=$request->serial_no;
        if ($request->subcategory === 'Computer') {
        $electronic->computer_type=$request->computer_type;
        $electronic->monitor_size=$request->monitor_size;
        $electronic->accessories=$request->accessories;
        }
        if ($request->subcategory === 'Phone') {
        $electronic->phone_type=$request->phone_type;
        }
        if ($request->subcategory === 'Television') {
            $electronic->size=$request->size;
            }
            if ($request->subcategory === 'Disk') {
                $electronic->disk_size=$request->disk_size;
                $electronic->disk_type=$request->disk_type;
                }
        $electronic->save();
      
       
    }
    if ($request->category === 'Furniture') {
        Furniture::create([
            'asset_id' => $assets->id,
            'furniture_type' => $request->furniture_type,
            'material' => $request->material,
            
        ]);
    }
   
    if ($request->category === 'Vehicle') {
        Vehicle::create([
            'asset_id' => $assets->id,
            'vehicle_type' => $request->vehicle_type,
            'cheses_no' => $request->cheses_no,
            'reg_no' => $request->reg_no,
            'brand' => $request->brand,
            'model' => $request->model,
        ]);
    }
     return response()->json([
        'success'=>true,
        'status'=>300,
        'message'=>'Asset Added Successfully!',
    ]);
}

public function placeorder(Request $request,$id)
{
    $asset=Asset::find($id);
    $user_id=Auth::user()->id;
    $depart_id=Auth::user()->depart_id;
    if($asset)
    {
        $order=new Order;
        $order->asset_id=$asset->id;
        $order->user_id= $user_id;
      $order->depart_id= $depart_id;
        $order->status='0';
        $order->save();
        if( $order->save())
        {
            $asset->flug='1';
            $asset->update();
        }
        return response()->json([
            'success'=>true,
            'status'=>300,
            'message'=>'Asset Ordered successfully!',
        ]);
    }
}

public function show($id){
        $asset= Asset::find($id);
       $electronic=Electronic::where('asset_id',$id)->first();
        $building=Building::where('asset_id',$id)->first();
        $furniture=Furniture::where('asset_id',$id)->first();
        $transport=Transport::where('asset_id',$id)->first();
        $infos = Info::with('user')->select('*')
       ->where('asset_id',$id)
       ->orderBy('created_at', 'desc')
       ->get()
       ->unique('user_id');
    return response()->json(['success'=>true,'status'=>300, 'data'=> $asset,'electronic'=>$electronic,'furniture'=>$furniture,'transport'=>$transport, 'building'=>$building,'info'=>$infos],200); 
}

public function assignElectronics(Request $request,$id)
{
      $electronic=Electronic::find($id);
    $asset=Asset::where('id',$electronic->asset_id)->first();
    if($asset)
    {
        if($asset->subcategory=="Computer"){
            $asset->staff_id = $request->staff_id;
            $asset->status=1;
        }
        if($asset->subcategory=="Television"){
            $asset->section_id = $request->section_id;
            $asset->status=1;
        }
        if($asset->subcategory=="Disk"){
            $asset->section_id = $request->section_id;
            $asset->status=1;
        }
        if($electronic->phone_type=="MobilePhone"){
            $asset->section_id = $request->section_id;
            $asset->status=1;
        }
        if($electronic->phone_type=="Landline"){
            $asset->office_id = $request->office_id;
            $asset->status=1;
        }
    }
   if($asset->update()){
       $modelAss=new IssuedAsset();
       if($asset->subcategory=="Computer"){
       $modelAss->staff_id=$request->staff_id;
       }
       if($asset->subcategory=="Television"){
        $modelAss->section_id=$asset->section_id;
        }
        if($asset->subcategory=="Disk"){
            $modelAss->section_id=$asset->section_id;
            }
       if($electronic->phone_type=="MobilePhone"){
       $modelAss->section_id=$request->section_id;
       }
       if($electronic->phone_type=="Landline"){
       $modelAss->office_id=$asset->office_id;
       }
       $modelAss->asset_id= $asset->id;
       $modelAss->condition= $asset->condition;
       $modelAss->status= 1;
       $modelAss->save();

       
   }
   return response()->json([
    'success'=>true,
    'status'=>300,
    'message'=>'Asset Assigned successfully!',
]);
}
public function assignFurniture(Request $request,$id)
{
      $furniture=Furniture::find($id);
    $asset=Asset::where('id',$furniture->asset_id)->first();
    if($asset)
    {
     $asset->office_id = $request->office_id;
     $asset->status=1;
    }
   if($asset->update()){
       $modelAss=new IssuedAsset();
       $modelAss->office_id=$asset->office_id;
       $modelAss->asset_id= $asset->id;
       $modelAss->save();
   }
   return response()->json([
    'success'=>true,
    'status'=>300,
    'message'=>'Asset Assigned successfully!',
]);
}

public function electronicUnassign(Request $request ,$id)
{
    $electronic=Electronic::find($id);
     $asset=Asset::where('id',$electronic->asset_id)->first();
  $issued=IssuedAsset::where('asset_id',$asset->id)->latest()->first();
  $issued_i=IssuedAsset::where('asset_id',$asset->id)->where('status',1)->latest()->first();
    if( $asset){
        if($asset->subcategory=="Computer") {
        $asset->staff_id = NULL;
    }
    if($asset->subcategory=="Television") {
        $asset->section_id = NULL;
    }
    
        $asset->condition =$request->condition;
        $asset->status =0;
        if($asset->update())
        {
            $curi=new IssuedAsset;
            $curi->asset_id = $id;
            if($asset->subcategory=="Computer") {
            $curi->staff_id= $issued->staff_id; 
            }
            if($asset->subcategory=="Television") {
                $curi->section_id= $issued->section_id; 
                }
            
            $curi->office_id= $issued->office_id; 
            $curi->condition= $asset->condition; 
            $curi->status=0; 
            $curi->save();
        }
        if($asset->update())

        // {
        //     $curi_i= $issued_i;
        //     $curi_i->delete();
        // }
        
        {
            $infos = new Track;
            $infos->asset_id = $id;
            $infos->staff_id= $issued->staff_id; 
            $infos->condition= $asset->condition; 
            $infos->status=0; 
            $infos->condition_i=$issued->condition; 
            $infos->reason=$request->reason; 
            $infos->save(); 
         }
        //  if($asset->update())
        //  {
        //      if($asset->status=='BROKEN')
        //      {
        //         $maintain=new Maintainance;
        //         $maintain->asset_id= $id;
        //         $maintain->condtn= $asset->status;
        //         $maintain->returned_at= $asset->created_at;
        //         $maintain->save();
        //      }
        //  }
    }
    return response()->json([
        'success'=>true,
        'status'=>300,
        'message'=>'Asset Unassigned successfully!',
    ]);
}

public function workshop()
{
    $maintains=Maintainance::with('asset')->where('flug',1)->get();
    $maintainsCount=Maintainance::with('asset')->where('flug',1)->count();
    return response()->json(['success'=>true,'status'=>300, 'data'=> $maintains,'count'=>$maintainsCount],200);
}

public function repair(Request $request,$id)
{
    $repair=Maintainance::find($id);
    $rep_id=$repair->asset_id;
    $maintains=Asset::where('id',$rep_id)->first();
    if( $repair)
    { 
        $repair->condtn=$request->condtn;
        if( $repair->update())
        {
            if( $repair->condtn=='DISPOSED'){
                $disposal=new Disposal;
                $disposal->asset_id= $rep_id;
                $disposal->condtn_m="DISPOSED";
                $disposal->save(); 

                $disp=$maintains; 
                $disp->status=$repair->condtn;
                $disp->control=0;
                $disp->update();
                if($disp->update())
                {
                   $repair->delete();
                }
            }elseif($repair->condtn=='REPAIRED'){
               $history=new Repair;
             $history->asset_id= $rep_id;
              $history->flug="BROKEN";
             $history->save();

             $repaired=$maintains;
             $repaired->status=$repair->condtn;
            
             $repaired->update();

             if($repaired->update())
             {
                $repair->delete();
             }
            } 
        }
        return response()->json([
            'success'=>true,
            'status'=>300,
            'message'=>'Asset Repaired successfully!',
        ]);
    }
}

public function disposal()
{
    $disposal=Disposal::with('asset')->where('flug',1)->get();
    $disposalCount=Disposal::with('asset')->where('flug',1)->count();
    return response()->json(['success'=>true,'status'=>300, 'data'=> $disposal,'count'=>$disposalCount],200);
}


public function destroy($id)
{
        $assets=Asset::find($id);
        $assets->delete();
        return response()->json([
            'success'=>true,
            'status'=>300,
            'message'=>'Asset Deleted successfully!',
        ]);
}
}
