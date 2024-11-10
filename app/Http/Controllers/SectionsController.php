<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections=sections::all();
        return view('sections.sections',compact('sections'));
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

        // $input = $request->all();

        // $b_exists=sections::where('section_name','=',$input['section_name'])->exists();

        // if ($b_exists) {
        //     session()->flash('Erorr','خطا القسم مسجل سابقا');
        //     return redirect('/sections');
        // }
        // else{
            $validatedData = $request->validate([
                'section_name' => ['required', 'unique:sections', 'max:255'],
                // 'description' => ['required'],
            ],
            [
                // 'section_name.required' =>'اكتب اسم القسم يعرص',
                // 'section_name.unique' =>'مينفعش تقرر اسم القسم يعرص',
                // 'description.required' =>'اكتب وصف القسم يعرص',
                'section_name.required'=>'يرجي ادخال اسم القسم',
                'section_name.unique'=>'  اسم القسم موجود مسبقا',
                // 'description.required'=>'يرجي ادخال البيان',
            ]);

            sections::create([
                'section_name'=>$request->section_name,
                'description'=>$request->description,
                'created_by'=>(Auth::user()->name),
            ]);
            // session()->flash('Add','تم اضافه االقسم بنجاح  ');
            return redirect('/sections')->with('Add','تم اضافه االقسم بنجاح ');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $sections = sections::findorfail($id);
        // او
        // $post = Post::where('id',$id)->first();
        // return view('sections.sections',compact('sectionss'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sections $sections)
    {
        $id = $request->id;
        $this->validate($request,[
            'section_name' => ['required','max:222','unique:sections,section_name,'.$id],
            // 'description' => ['required'],
        ],
        [
            // 'section_name.required' =>'اكتب اسم القسم يعرص',
            // 'section_name.unique' =>'مينفعش تقرر اسم القسم يعرص',
            // 'description.required' =>'اكتب وصف القسم يعرص',
            'section_name.required'=>'يرجي ادخال اسم القسم',
            'section_name.unique'=>'  اسم القسم موجود مسبقا',
            // 'description.required'=>'يرجي ادخال البيان',
        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name'=> $request->section_name,
            'description'=> $request->description,
            ]
        );

        return redirect('/sections')->with('edit','تم تعديل االقسم بنجاح ');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        return redirect('/sections')->with('delete','تم حذف االقسم بنجاح ');
    }
}
