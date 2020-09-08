<?php

namespace App\Http\Controllers;

use App\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $randomnumber=array();
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $stringpieces = str_split($permitted_chars);
        $b = $rowcount = $this->rowCount();

        for ($i=$rowcount+1; $i <= $request['count']+$rowcount; $i++) { 
            $c++;
            $b++; 

            $tempnumber = substr(sprintf("%06d", $b), -5);


            $index =substr(sprintf("%07d", $i), 0, -5 );

            $number = $stringpieces[(int)$index].$tempnumber;
            
            $pieces = str_split($number); 

            foreach ($pieces as $key => $value) {
                if ($value === "0") {
                    $pieces[$key] = substr(str_shuffle($permitted_chars), 0, 1);
                }
            }
            // echo $i." ".$tempnumber." ". implode("", $pieces)." ".$number;
            $randomnumber[] = implode("", $pieces);
            if ($c == "70000" ) {
               $this->curlToLumen($randomnumber);
               $randomnumber=array();
               $c = 0;
            }

        }
        //call to API balance array
        if ($randomnumber) {
           $this->curlToLumen($randomnumber);
        }  
        echo "<br>";
        return response('Success insert '. $request['count'].' data');
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


    public function rowCount(){
        $url = env("API_URL")."/uniquecode/count";
  
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);
        return $response;

    }
}
