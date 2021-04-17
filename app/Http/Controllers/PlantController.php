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

class PlantController extends Controller {
    //

    function addPlant ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'name' => 'required',
            'price' => 'required',
            'avatar' => 'required|mimes:jpeg,jpg,png,gif|max:3000',
            'stock' => 'required',
            'plant_categories_id' => 'required',
            'plant_type_id' => 'required',

        ] );
        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        }

        if ( Plant::where( 'name', $req->name )
        ->where( 'price', $req->price )
        ->where( 'avatar', $req->avatar )
        ->where( 'plant_categories_id', $req->plant_categories_id )
        ->where( 'plant_type_id', $req->plant_type_id )->exists() ) {

            return response()->json( ['status' => 'error',
            'message' => 'Plant Details Already Exists'], 401 );
        } else {

            $image = $req->file( 'avatar' );
            $imageName = time().'.'.$image->extension();
            $image->move( public_path( 'plant_images' ), $imageName );

            Plant::insert( [

                'name' => $req->name,
                'price' => $req->price,
                'avatar' => $imageName,
                'stock' => $req->stock,
                'plant_categories_id' => $req->plant_categories_id,
                'plant_type_id' => $req->plant_type_id,

            ] );

            return response()->json( ['status' => 'success',
            'message' => 'Plant Details Added Successfully'], 200 );

        }
    }

    public function updatePlant( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'plant_id' => 'required',
            'name' => 'sometimes',
            'price' => 'sometimes',
            'avatar' => 'sometimes|image:jpeg,png,jpg|max:4000',
            'stock' => 'sometimes',
            'plant_categories_id' => 'required',
            'plant_type_id' => 'required',

        ] );
        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Plant::where( 'id', $req->plant_id )->exists() ) {

                $plant = Plant::where( 'id', $req->plant_id );

                if ( $req->name ) {

                    $plant->update( [

                        'name' => $req->name,

                    ] );
                }

                if ( $req->price ) {

                    $plant->update( [

                        'price' => $req->price,

                    ] );
                }

                if ( $req->avatar ) {

                    $image = $req->file( 'avatar' );
                    $imageName = time() . '.' . $image->extension();
                    $image->move( public_path( 'plant_images' ), $imageName );

                    $plant->update( [

                        'avatar' => $req->imageName,

                    ] );
                }

                if ( $req->stock ) {

                    $plant->update( [

                        'stock' => $req->stock,

                    ] );
                }

                if ( $req->plant_categories_id ) {

                    $plant->update( [

                        'plant_categories_id' => $req->plant_categories_id,

                    ] );
                }

                if ( $req->plant_type_id ) {

                    $plant->update( [

                        'plant_type_id' => $req->plant_type_id,

                    ] );
                }

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Plant Updated Successfully',

                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'Plant does not exists',

                ], 500 );
            }
        }
    }

    public function deletePlant( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'plant_id' => 'required',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Plant::where( 'id', $req->plant_id )->exists() ) {

                Plant::where( 'id', $req->plant_id )
                ->delete();

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Plant Deleted Successfully',

                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'Plant does not exists',

                ], 500 );
            }
        }
    }

    public function showPlant( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'plant_id' => 'required',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Plant::where( 'id', $req->plant_id )->exists() ) {

                $plant = Plant::where( 'id', $req->plant_id )->first();

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Plant Updated Successfully',
                    'plant' => $plant,

                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'Plant does not exists',

                ], 500 );
            }
        }
    }

    public function allPlants () {

        $plants = Plant::paginate( 10 );

        return response()->json( [

            'status' => 'success',
            'plant' => $plants,

        ], 200 );
    }

    // get all plant categories ...

    public function allPlantcategories () {

        $plantcategories =  DB::table( 'plantcategories' )->get();

        return response()->json( [

            'status' => 'success',
            'plantCategories' => $plantcategories,

        ], 200 );
    }

    // get all plant types ...

    public function allPlanttypes () {

        $planttypes = DB::table( 'planttypes' )->get();

        return response()->json( [

            'status' => 'success',
            'plantTypes' => $planttypes,

        ], 200 );
    }

}

