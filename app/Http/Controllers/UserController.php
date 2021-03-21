<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller {

    function list( $id = null ) {
        return $id?User::find( $id ):User::all();
    }

    function login( Request $req ) {

        $data = User::where( 'email', $req->email )->where( 'password', $req->password )->get();
        return $data;

    }

    function add( Request $req ) {
        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make( $req->password );
        $result = $user->save();
        if ( $result != null ) {
            return 'Data saved Successfully!';
        } else {
            return 'Data Not saved!';
        }
    }

    function remove( Request $r ) {
        $data = User::where( 'email', $r->email )->delete();
        if ( $data ) {
            return ['result'=>"user {$r->email} is deleted!"];
        } else {
            return ['result'=>'Failed to delete!'];

        }
    }

}
