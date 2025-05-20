<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadsController extends Controller
{

    //todo: сделать реквест
    public function addLead(Request $request){
//        $data = $request->all();
//        $lead = new Lead($data);

//        $lead->save();
        return response('lead created',201);
    }
}
