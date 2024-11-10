<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class archiveController extends Controller
{
    // public function index(){
    //     $invoices = invoices::onlyTrashed()->get();
    //     return view("invoices.invoices_archive",compact("invoices"));
    // }
    // public function archive_invoice(Request $request){
    //     $invoices = invoices::findOrFail($request->invoice_id);
    //     $invoices->delete();
    //     return redirect()->route('archive')->with("success_archive","تمت عمليه الارشفه");
    // }

    // public function destroy(Request $request){
    //     $invoices = invoices::withTrashed()->where('id', $request->invoice_id)
    //     ->forceDelete();
    //     return redirect()->route('archive')->with("success_delete","تمت عمليه الحذف بنجاح ");
    // }



}
