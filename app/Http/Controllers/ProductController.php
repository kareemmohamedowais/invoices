<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections=sections::all();
        $products=product::all();
        return view('products.products',compact('sections','products'));
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
        $validateData = $request->validate([
            'product_name' => 'required|max:255|',
            'description' => 'max:255|nullable',
        ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
        ]);

        product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        return redirect()->route('products.index')->with('add','تم اضافه المنج بنجاح');

    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = sections::where('section_name', $request->section_name)->first()->id;
        $products = product::findOrFail($request->pro_id);
        $validateData = $request->validate([
            'product_name' => 'required|max:255|',
            'description' => 'max:255',
        ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
        ]);

        $products->update([
            'product_name'=> $request->product_name,
            'description'=> $request->description,
            'section_id'=> $id,
        ]);
        return redirect()->route('products.index')->with('edit','تم تعديل المنج بنجاح');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $products = product::findOrFail($request->pro_id);
        $products->delete();
        return redirect()->route('products.index')->with('delete','تم حذف المنج بنجاح');

    }
}
