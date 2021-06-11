<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Plant;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {
    //

    public function add_order ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
            'shipping_address' => 'required',
            'phone_no' => 'required',
            'order_expecting_date' => 'required',
            'delivery_condition' => 'required',
            'plant_ids' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( User::where( 'id', $req->user_id )->exists() ) {

                $i = 0;

                foreach ( $req->plant_ids as $plant_id ) {

                    if ( Plant::where( 'id', $plant_id )->exists() ) {

                        $plant = Plant::where( 'id', $plant_id )->first();

                        Order::insert( [

                            'user_id' => $req->user_id,
                            'shipping_address' => $req->shipping_address,
                            'price' => $plant->price,
                            'phone_no' => $req->phone_no,
                            'order_expecting_date' => $req->order_expecting_date,
                            'delivery_condition' => $req->delivery_condition,
                            'quantity' => $req->quantity[$i],
                            'plant_id' => $plant_id,
                            'order_status' => 'pending',

                        ] );

                    } else {

                        return response()->json( [

                            'status' => 'error',
                            'message' => 'Plant Does not Exists',
                        ], 404 );
                    }

                    $i++;

                }
                $order = Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->get();

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Order Addedd Successfully',
                    'order' => $order,
                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'User Does not Exists',
                ], 404 );

            }

        }
    }

    public function view_order ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->exists() ) {

                $order = Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->get();

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Order Fetched Successfully',
                    'order' => $order,
                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'success',
                    'message' => 'you have no pending orders...',
                ], 200 );
            }
        }
    }

    public function delete_order ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->exists() ) {

                Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->delete();

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Order Deleted Successfully',

                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'success',
                    'message' => 'you have no pending orders...',
                ], 200 );
            }
        }
    }

    public function update_order ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
            'shipping_address' => 'sometimes',
            'phone_no' => 'sometimes',
            'order_expecting_date' => 'sometimes',
            'delivery_condition' => 'sometimes',
            'plant_ids' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            $i = 0;

            foreach ( $req->pland_ids as $plant_id ) {

                if ( Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending', 'plant_id' => $plant_id] )->exists() ) {

                    $plant = Plant::where( 'id', $plant_id )->first();

                    Order::insert( [

                        'user_id' => $req->user_id,
                        'shipping_address' => $req->shipping_address,
                        'price' => $plant->price,
                        'phone_no' => $req->phone_no,
                        'order_expecting_date' => $req->order_expecting_date,
                        'delivery_condition' => $req->delivery_condition,
                        'quantity' => $req->quantity[$i],
                        'plant_id' => $plant_id,
                        'order_status' => 'pending',

                    ] );

                } else {

                    return response()->json( [

                        'status' => 'error',
                        'message' => 'Plant Does not Exists',
                    ], 404 );
                }

                $i++;
            }

            $order = Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->get();

            return response()->json( [

                'status' => 'success',
                'message' => 'Order Addedd Successfully',
                'order' => $order,
            ], 200 );
        }

    }

    // public function add_order ( Request $req ) {

    //     $validator = Validator::make( $req->all(), [

    //         'user_id' => 'required',
    //         'shipping_address' => 'required',
    //         'phone_no' => 'required',
    //         'order_expecting_date' => 'required',
    //         'delivery_condition' => 'required',
    //         'quantity' => 'required',
    //         'plant_id_1' => 'required',

    //     ] );

    //     if ( $validator->fails() ) {

    //         return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

    //     } else {

    //         if ( $req->plant_id_1 ) {

    //             if ( Plant::where( 'id', $req->plant_id_1 )->exists() ) {

    //                 $plant = Plant::where( 'id', $req->plant_id_1 )->first();

    //                 Order::insert( [

    //                     'user_id' => $req->user_id,
    //                     'shipping_address' => $req->shipping_address,
    //                     'price' => $plant->price,
    //                     'phone_no' => $req->phone_no,
    //                     'order_expecting_date' => $req->order_expecting_date,
    //                     'delivery_condition' => $req->delivery_condition,
    //                     'quantity' => $req->quantity,
    //                     'plant_id' => $req->plant_id_1,
    //                     'status' => 'pending',

    //                 ] );

    //                 return response()->json( [

    //                     'status' => 'success',
    //                     'message' => 'Order Added Successfully',

    //                 ], 200 );

    //             } else {

    //                 return response()->json( [

    //                     'status' => 'error',
    //                     'message' => 'Plant_id Does not Exists in Plants',

    //                 ], 404 );
    //             }
    //         }

    //     }
    // }
}
