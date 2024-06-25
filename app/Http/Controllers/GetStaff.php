<?php

namespace App\Http\Controllers;

use App\Http\Resources\Staff\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetStaff extends Controller
{
    /**
     * Handle the incoming request.
    */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return StaffResource::collection(Staff::all());
    }
}
