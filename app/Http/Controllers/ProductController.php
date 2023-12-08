<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = product::latest()->paginate(10);

        return view('products.index',compact('products'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation
            $request->validate([ 
                'name' =>'required',
                'details' =>'required',
                'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // fatch all data from form
            $input = $request->all();

            if ($image = $request->file('image')) {
                $destainationPath = 'image/';
                $profileImage = date('YmdHis'). ".". $image->getClientOriginalExtension();
                $image->move($destainationPath, $profileImage);
                $input['image'] = "$profileImage";
             }
             product::create($input);

             return redirect()->route('products.index')
             ->with('success','Product created successfully.');
             
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        $request->validate([
            'name' =>'required',
            'details' =>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destainationPath = 'image/';
            $profileImage = date('YmdHis'). ".". $image->getClientOriginalExtension();
            $image->move($destainationPath, $profileImage);
            $input['image'] = "$profileImage";
         }else{
            unset($input['image']);
         }
         $product->update($input);
         return redirect()->route('products.index')->with('success','Product updated successfully');

            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
