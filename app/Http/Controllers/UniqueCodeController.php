<?php

namespace App\Http\Controllers;

use App\UniqueCode;
use Illuminate\Http\Request;

class UniqueCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('uniquecode');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   $c = 0;
        $b = 0;
        for ($i=0; $i < $request['count']; $i++) { 
            $c++;
            $b++;
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $randomnumber[] = substr(str_shuffle($permitted_chars), 0, 6);
            if ($c == "50000" ) {
               $this->curlToLumen($randomnumber);
               $randomnumber=array();
               $c = 0;
            }

        }
        if ($randomnumber) {
           $this->curlToLumen($randomnumber);
        }  

        return response('Success insert '. $request['count']. ' data');
    }

    public function curlToLumen($randomnumber=array())
    {
        $post [ 'uniquenumber'] = json_encode($randomnumber);
        // $post [ 'uniquenumber'] = $randomnumber;

        $url = env("API_URL")."/uniquecode/store";
  
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Receive server response ...
        if (!curl_exec($ch)) {
            return response('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        }

        curl_close ($ch);
        return response('Success');

    }   
}
