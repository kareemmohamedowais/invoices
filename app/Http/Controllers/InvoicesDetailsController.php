<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use App\Models\invoices_attachment;
use Illuminate\Support\Facades\Storage;


class InvoicesDetailsController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }


    public function showDetails($id){
        $invoices = invoices::where('id',$id)->first();
        $details = invoices_details::where('id_invoice',$id)->get();
        $attachments = invoices_attachment::where('invoice_id',$id)->get();
        return view('invoices.invoices_details',
        compact('invoices','details','attachments'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $invoices = invoices_attachment::findOrFail( $request->id_file);
        // dd($invoices);
        Storage::disk('public_uploads')
        ->delete($invoices->invoice_number.'/'.$invoices->file_name);
        $invoices->delete();
        // Storage::delete('Attachments/'.$invoices->invoice_number.'/'.$invoices->file_name);
        session()->flash('delete','تم حذف المرفق بنجاح');
        return back();
    }

    public function get_file($invoice_number, $file_name){
        // $contents= Storage::url('Attachments/'.$invoice_number.'/'.$file_name);
        // dd($contents);
        // return Storage::download('Attachments/'.$invoice_number.'/'.$file_name, $file_name);
        return response()->download('Attachments/'.$invoice_number.'/'.$file_name, $file_name);
    }
    public function view_file($invoice_number, $file_name){
        // $files = asset('Attachments/'.$invoice_number.'/'.$file_name);
        // $files = Storage::disk('public_uploads')
        // ->files($invoice_number.'/'. $file_name);
        // dd( $files );
        return response()->file('Attachments/'.$invoice_number.'/'.$file_name);
    }
}
