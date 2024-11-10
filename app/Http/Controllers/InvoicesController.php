<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use Illuminate\Support\Facades\DB;
use App\Models\invoices_attachment;
use App\Notifications\Invoicecreate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoices::all();

        return view('invoices.invoices', compact('invoices'));
    }
    public function invoices_unpaid()
    {
        $invoices = invoices::where('Value_Status',2)->get();

        return view('invoices.invoices', compact('invoices'));
    }
    public function invoices_paid()
    {
        $invoices = invoices::where('Value_Status',1)->get();

        return view('invoices.invoices', compact('invoices'));
    }
    public function invoices_patial_paid()
    {
        $invoices = invoices::where('Value_Status',3)->get();

        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $file = $request->file('pic');
            $file_name = $file->getClientOriginalName();
            $invoice_number = $request->invoice_number;


            $attachments = new invoices_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $fileName = $request->pic->getClientOriginalName();
            // $request->pic->move(public_path('Attachments/' . $invoice_number),
            // $fileName);
            $request->file('pic')
            ->storeAs($invoice_number,$fileName,'public_uploads');
        }


        //    // $user = User::first();
        //    // Notification::send($user, new AddInvoice($invoice_id));

        $user =User::first();
        $user->notify(new Invoicecreate($invoice_id));

        // $user = User::get();
        // $invoices = invoices::latest()->first();
        // Notification::send($user, new \App\Notifications\Add_invoice_new($invoices));



        // event(new MyEventClass('hello world'));

        // session()->flash('add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->route('invoices.index')
        ->with('add', 'تم اضافة الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id',$id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    public function Status_update($id,Request $request){
        $invoices = invoices::findOrFail( $id );


        if($request->status === 'مدفوعة'){
            $invoices->update([
                'Status'=> $request->status,
                'Value_Status' =>1,
                'Payment_Date' =>$request->payment_date,
            ]);
            // dd($request->invoice_id);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->section,
                'Status' => $request->status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Status'=> $request->status,
                'Value_Status' =>3,
                'Payment_Date' =>$request->payment_date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->section,
                'Status' => $request->status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices $invoices,$id)
    {
        $invoices = Invoices::where('id',$id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoices',compact('invoices','sections'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);


        // session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index')
        ->with('edit', 'تم تعديل الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // $id = $request->id;
        // $invoices = invoices::where('id',$id);
        // $attachment = invoices_attachment::where('invoice_id',$id)->first();
        // dd($attachment);
        // if(!empty( $attachment->invoice_number )){
        //     // Storage::disk('public_uploads')
        //     // ->delete($attachment->invoice_number.'/'.$attachment->file_name);
        //     Storage::disk('public_uploads')->deleteDirectory( $attachment->invoice_number);
        // }
        // $invoices->forceDelete();
        // return redirect()->route('invoices.index')
        // ->with('delete_invoice', 'تم حذف الفاتورة بنجاح');
    }
    public function force_delete(Request $request)
    {
        $id = $request->invoice_id;
        // return $id;
        $invoices = invoices::where('id',$id);
        $attachment = invoices_attachment::where('invoice_id',$id)->first();
        if(!empty( $attachment->invoice_number )){
            // Storage::disk('public_uploads')
            // ->delete($attachment->invoice_number.'/'.$attachment->file_name);
            Storage::disk('public_uploads')->deleteDirectory( $attachment->invoice_number);
        }
        $invoices->forceDelete();
        return redirect()->route('invoices.index')
        ->with('delete_invoice', 'تم حذف الفاتورة بنجاح');
    }



    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function print_invoice($id)
{
    $invoices = invoices::where("id",$id)->first();

    return view('invoices.print_invoice',compact('invoices'));
}
}
