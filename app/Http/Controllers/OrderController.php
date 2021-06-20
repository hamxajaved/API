<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Plant;
use App\Models\Order_product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class OrderController extends Controller {
    //

    public function add_order ( Request $req ) {

        $validator = Validator::make( $req->all(), [
            'user_id' => 'required',
            'shipping_address' => 'required',
            'phone_no' => 'required',
            'order_expecting_date' => 'required',
            'delivery_condition' => 'required',
            'plants_data' => 'required|array|min:1',
        ] );
        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( User::where( 'id', $req->user_id )->exists() ) {

                $order = new Order;

                $order->user_id = $req->user_id;
                $order->shipping_address = $req->shipping_address;
                $order->phone_no = $req->phone_no;
                $order->order_expecting_date = $req->order_expecting_date;
                $order->delivery_condition = $req->delivery_condition;
                $order->order_status = 'pending';
                $order->save();

                foreach ( $req->plants_data as $plant ) {

                    if ( Plant::where( 'id', $plant['id'] )->exists() ) {

                        $plnt = Plant::where( 'id', $plant['id'] )->first();
                        Order_product::insert( [

                            'name' => $plnt->name,
                            'price' => $plnt->price,
                            'order_id' => $order->id,
                            'quantity' => $plant['quantity'],
                            'plant_id' => $plant['id'],

                        ] );

                    } else {
                        return response()->json( [

                            'status' => 'error',
                            'message' => 'Plant Does not Exists',
                        ], 404 );
                    }

                }
                $order_plants = Order_product::where( 'order_id', $order->id )->get();
                $order['order_plants'] = $order_plants;
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
            'order_id' => 'required',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Order::where( ['id' => $req->order_id, 'order_status' => 'pending'] )->exists() ) {
                $order = Order::where( 'id', $req->order_id )->first();
                $order_plants = Order_product::where( 'order_id', $req->order_id )->get();
                $order['order_plants'] = $order_plants;
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

    public function view_all_orders ( Request $req ) {

        $validator = Validator::make( $req->all(), [
            'user_id' => 'required',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Order::where( ['user_id' => $req->user_id, 'order_status' => 'pending'] )->exists() ) {
                $orders = Order::where( 'user_id', $req->user_id )->get();
                foreach ( $orders as $order ) {
                    $order_plants = Order_product::where( 'order_id', $order->id )->get();
                    $order['order_plants'] = $order_plants;
                }
                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Order Fetched Successfully',
                    'orders' => $orders,
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
                Order_product::where( 'order_id', $order->id )->delete();

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
            'shipping_address' => 'required',
            'phone_no' => 'required',
            'order_expecting_date' => 'required',
            'delivery_condition' => 'required',
            'plants_data' => 'required|array|min:1',
        ] );
        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( User::where( 'id', $req->user_id )->exists() ) {

                $order = new Order;

                $order->user_id = $req->user_id;
                $order->shipping_address = $req->shipping_address;
                $order->phone_no = $req->phone_no;
                $order->order_expecting_date = $req->order_expecting_date;
                $order->delivery_condition = $req->delivery_condition;
                $order->order_status = 'pending';
                $order->save();

                foreach ( $req->plants_data as $plant ) {

                    if ( Plant::where( 'id', $plant['id'] )->exists() ) {

                        $plant = Plant::where( 'id', $plant['id'] )->first();
                        Order_product::save( [

                            'name' => $plant->name,
                            'price' => $plant->price,
                            'order_id' => $order->id,
                            'quantity' => $plant['quantity'],
                            'plant_id' => $plant['id'],

                        ] );

                    } else {
                        return response()->json( [

                            'status' => 'error',
                            'message' => 'Plant Does not Exists',
                        ], 404 );
                    }

                }
                $order_plants = Order_product::where( 'order_id', $order->id )->get();
                return response()->json( [

                    'status' => 'success',
                    'message' => 'Order Addedd Successfully',
                    'order' => $order,
                    'order_plants' => $order_plants,
                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'User Does not Exists',
                ], 404 );

            }

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
