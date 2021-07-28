<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Plant;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller {
    //

    public function add_to_cart ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'plant_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Cart::where( [

                'plant_id' => $req->plant_id,
                'user_id' => $req->user_id,

            ] )->exists() ) {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'Plant Already Exists in Cart',

                ], 500 );

            } else {

                Cart::insert( [

                    'plant_id' => $req->plant_id,
                    'user_id' => $req->user_id,
                    'quantity' => $req->quantity,

                ] );

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Plant Added to Cart Successfully',

                ], 200 );
            }
        }
    }

    public function show_cart ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Cart::where( 'user_id', $req->user_id )->exists() ) {

                $plants = Cart::where( 'user_id', $req->user_id )->get();

                $cart = [];

                foreach ( $plants as $plant ) {

                    $cart_product = Plant::where( 'id', $plant->plant_id )->first();

                    $cart_product->quantity = $plant->quantity;

                    array_push( $cart, $cart_product );
                }

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Products in Cart Fetched Successfully',
                    'cart' => $cart,

                ], 200 );

            } else {

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Cart is Empty',

                ], 200 );

            }
        }
    }

    public function delete_from_cart ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
            'plant_id_in_cart' => 'required',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Cart::where( 'user_id', $req->user_id )->exists() ) {

                if ( Cart::where( 'plant_id', $req->plant_id_in_cart )->exists() ) {

                    Cart::where( 'plant_id', $req->plant_id_in_cart )->delete();

                    return response()->json( [

                        'status' => 'success',
                        'message' => 'Plant Deleted from Cart',

                    ], 200 );

                } else {

                    return response()->json( [

                        'status' => 'error',
                        'message' => 'Plant does not exists in Cart',

                    ], 404 );

                }

            } else {

                return response()->json( [

                    'status' => 'success',
                    'message' => 'Your Cart is Empty',

                ], 200 );

            }
        }
    }

    public function order_from_cart2 ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
            'shipping_address' => 'required',
            'phone_no' => 'required',
            'order_expecting_date' => 'required',
            'delivery_condition' => 'required',
            // 'plant_ids' => 'required|array|min:1',
            // 'quantities' => 'required|array|min:1',

        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Cart::where( 'user_id', $req->user_id )->exists() ) {

                $products = Cart::where( 'user_id', $req->user_id )->get();

                foreach ( $products as $product ) {

                    $plant = Plant::where( 'id', $product->plant_id )->first();

                    Order::insert( [

                        'user_id' => $req->user_id,
                        'shipping_address' => $req->shipping_address,
                        'price' => $plant->price,
                        'phone_no' => $req->phone_no,
                        'order_expecting_date' => $req->order_expecting_date,
                        'delivery_condition' => $req->delivery_condition,
                        'quantity' => $product->quantity,
                        'plant_id' => $product->plant_id,
                        'order_status' => 'pending',

                    ] );

                    return response()->json( [

                        'status' => 'success',
                        'message' => 'You Order Placed Successfully',

                    ], 200 );

                }

            } else {

                return response()->json( [

                    'status' => 'success',
                    'message' => 'You have no product in Cart',
                ], 200 );

            }
        }

    }

    public function order_from_cart ( Request $req ) {

        $validator = Validator::make( $req->all(), [

            'user_id' => 'required',
            'shipping_address' => 'required',
            'phone_no' => 'required',
            'order_expecting_date' => 'required',
            'delivery_condition' => 'required',
        ] );

        if ( $validator->fails() ) {

            return response()->json( ['status' => 'error', 'error' => $validator->errors()], 400 );

        } else {

            if ( Cart::where( 'user_id', $req->user_id )->exists() ) {

                $carts = Cart::where( 'user_id', $req->user_id )->get();

                foreach ( $carts as $cart ) {

                    $plant = Plant::where( 'id', $cart->plant_id )->first();

                    Order::insert( [

                        'user_id' => $req->user_id,
                        'shipping_address' => $req->shipping_address,
                        'price' => $plant->price,
                        'phone_no' => $req->phone_no,
                        'order_expecting_date' => $req->order_expecting_date,
                        'delivery_condition' => $req->delivery_condition,
                        'quantity' => $cart->quantity,
                        'plant_id' => $cart->plant_id,
                        'order_status' => 'pending',

                    ] );

                    return response()->json( [

                        'status' => 'success',
                        'message' => 'You Order has been successfullt replaced',
                    ], 200 );

                }

            } else {

                return response()->json( [

                    'status' => 'error',
                    'message' => 'You have no product in Cart',
                ], 200 );

            }
        }

    }
}