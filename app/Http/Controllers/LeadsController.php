<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Status;
use Illuminate\Http\Request;

class LeadsController extends Controller
{

    //todo: сделать реквест
    public function addLead(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|min:10|max:20|regex:/^\+?[0-9]{10,20}$/',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $source = $request->header('x-token', 'unknown');

        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $lead = Lead::create([
            'phone' => $validated['phone'],
            'status_id' => Status::getDefaultStatus()->id,
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'description' => $validated['description'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'source' => $source,
            'ip' => $ip,
            'user_agent' => $userAgent,

        ]);


        return response()->json([
            'message' => 'Lead created successfully',
            'lead_id' => $lead->id,
            'status' => $lead->status->name
        ], 201);
    }
}
