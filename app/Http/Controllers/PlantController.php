<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Plant;
use App\Models\Plantcategory;
use App\Models\Planttype;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    //

    function addPlant (Request $req ) {

        $validator=Validator::make($req->all(), [

            'name' => 'required',
            'price' => 'required',
            'avatar' => 'required',
            'stock' => 'required',
            'plant_categories_id' => 'required',
            'plant_type_id' => 'required',
            

        ]);
        if ($validator->fails()) {

            return response()->json(['status' => "error", 'error' => $validator->errors()], 400);             
        } 

        

        if ( Plant::where('name', $req->name)
                    ->where('price', $req->price)
                    ->where('avatar', $req->avatar)
                    ->where('plant_categories_id', $req->plant_categories_id)
                    ->where('plant_type_id', $req->plant_type_id)->exists())
                 { 

                    return response()->json(['status' => "error", 
                    'message' => "Plant Details Already Exists"], 401);
                 }

                 else {

                       
                    $image = $req->file('avatar');
                    $imageName = time().'.'.$image->extension();
                    $image->move(public_path('plant_images'), $imageName);            

                    Plant::insert([

                        'name' => $req->name,
                        'price' => $req->price,
                        'avatar' => $imageName,
                        'stock' => $req->stock,
                        'plant_categories_id' => $req->plant_categories_id,
                        'plant_type_id' => $req->plant_type_id,
                        
                    ]);

                    return response()->json(['status' => "success", 
                    'message' => "Plant Details Added Successfully"], 200);

                 }
            } 
    }

