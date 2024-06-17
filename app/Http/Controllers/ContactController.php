<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\user\ContactMail;
use App\Mail\admin\ContactMail as AdminContactMail;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    public function store(ContactRequest $request)
    {
        try {
            $attributes = $request->only([
                'contactType',
                'name',
                'companyName',
                'telNumber',
                'email',
                'content',
            ]);
            $this->contact->contact_type =$request->contactType;
            $this->contact->name = $request->name;
            $this->contact->company_name =$request->companyName;
            $this->contact->tel_number =$request->telNumber;
            $this->contact->email =$request->email;
            $this->contact->content =$request->content;
            $this->contact->save();

            // mail送信
            Mail::send(new ContactMail($attributes));
            Mail::send(new AdminContactMail($attributes));

            return response()->json([
                'message' => 'お問い合わせ送信が完了しました'
            ], 200);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'お問い合わせ送信に失敗しました'
            ], 400);
        }
    }
}
