<?php

namespace App\Http\Controllers;

use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', 'App\Category');
        $categories = Category::orderBy('display_order')->get();
        return view('admin.categories.index',[
            'categories' => $categories,
        ]);
    }

    public function upsert(Request $request){
        $this->authorize('manage', 'App\Category');
        // return response(['message' => 'success'], 200);
        $categories = $request->post('categories');
        foreach($categories as $cat){
            if($cat['id']){
                $cat['created_at'] = $cat['created_at']?Carbon::parse($cat['created_at'])->format('Y-m-d H:i:s'):$cat['created_at'];
                $cat['updated_at'] = $cat['updated_at']?Carbon::parse($cat['updated_at'])->format('Y-m-d H:i:s'):$cat['created_at'];
                Category::where('id', $cat['id'])->update($cat);
            } else {
                Category::create($cat);
            }
        }
        return ['message' => 'success', 'categories' => Category::all()];
        // return response(['message' => 'success'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return ['success' => true];
    }
}
