<?php
namespace App\Utils;

class TreatRequest{
    static public function getDataRequest($request)
    {
        if($request->headers->get('Content-Type') == 'application/json'){
            $data= $request->toArray();
        }else{
            $data= $request->request->all();
        }
        return $data;
    }
}