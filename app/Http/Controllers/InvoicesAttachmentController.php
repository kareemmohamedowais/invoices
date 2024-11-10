<?php

namespace App\Http\Controllers;

use App\Models\invoices_attachment;
use Illuminate\Http\Request;

class InvoicesAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'pic' => 'mimes:png,jpg,pdf,jpeg',
        ],[
            'pic.mimes' => 'صيغه المرفق يجب ان تكون png,jpg,pdf,jpeg',
        ]);
        
        // if ($request->hasFile('pics')) {
        //     foreach ($request->file('pics') as $file) {
        //
        //     }
        // }

        $file = $request->file('pic');
        $filename = $file->getClientOriginalName();

        $attachments = new invoices_attachment();
        $attachments->file_name = $filename;
        $attachments->invoice_number = $request->invoice_number;
        $attachments->invoice_id = $request->invoice_id;
        $attachments->Created_by = auth()->user()->name;
        $attachments->save();

        $request->file('pic')
        ->storeAs($request->invoice_number,$filename,'public_uploads');

        session()->flash('add','تم اضافه المرفق بنجاح');
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_attachment $invoices_attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_attachment $invoices_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_attachment $invoices_attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_attachment $invoices_attachment)
    {
        //
    }
}
