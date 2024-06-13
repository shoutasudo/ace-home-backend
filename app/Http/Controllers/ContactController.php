<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     *  API POST
     */
    public function store(Request $request)
    {
        try {
            $this->contact->contact_type =$request->contactType;
            $this->contact->name = $request->name;
            $this->contact->company_name =$request->companyName;
            $this->contact->tel_number =$request->telNumber;
            $this->contact->email =$request->email;
            $this->contact->content =$request->content;
            $this->contact->save();

            return response()->json([
                'status' => 'success'
            ], 200);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'failed'
            ], 400);
        }
    }
}
