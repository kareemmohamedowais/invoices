<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class invoicesarchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $invoices = invoices::onlyTrashed()->get();
        return view("invoices.invoices_archive",compact("invoices"));
    }
    public function archive_invoice(Request $request){
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->delete();
        return redirect()->route('archive')->with("success_archive","تمت عمليه الارشفه");
    }

    public function destroy(Request $request){
        $invoices = invoices::withTrashed()->where('id', $request->invoice_id);
        $attachment = invoices_attachment::where('invoice_id',$request->invoice_id)->first();
        if(!empty( $attachment->invoice_number )){
            // Storage::disk('public_uploads')
            // ->delete($attachment->invoice_number.'/'.$attachment->file_name);
            Storage::disk('public_uploads')->deleteDirectory( $attachment->invoice_number);
        }
        $invoices->forceDelete();
        return redirect()->route('archive')->with("success_delete","تمت عمليه الحذف بنجاح ");
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::withTrashed()->where('id', $request->invoice_id)
        ->restore();
        return redirect()->route('invoices.index')->with("success_archive","تمت عمليه الغاء الارشفه");
    }

    /**
     * Remove the specified resource from storage.
     */

}
