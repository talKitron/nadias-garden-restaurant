<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItemPost;
use App\MenuItem;
use App\MenuItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function App\deleteImageFromDisk;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Update or insert a new MenuItem to the database.
     * Will add newly uploaded images to a MenuItemImage model for the corresponding MenuItem
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upsert(Request $request) {
        $this->authorize('manage', 'App\MenuItem');
        $validated = $request->validate([
            'name' => 'required|max:128',
            'description' => 'required|max:512',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|numeric',
            'images' => 'required|array',
            'images.*' => 'required|string'
        ]);
        unset($validated['images']);
        if ($request['id']) {
            MenuItem::where('id', $request['id'])->update($validated);
            foreach ($request->images as $image) {
                MenuItemImage::firstOrCreate(['image' => $image],[
                    'image' => $image,
                    'menu_item_id' => $request->id,
                    'user_id' => Auth::user()->id,
                ]);
            }
            
        } else {
            $menuItem = MenuItem::create($validated);
            foreach ($request->images as $image) {
                MenuItemImage::create([
                    'image' => $image,
                    'menu_item_id' => $menuItem->id,
                    'user_id' => Auth::user()->id,
                ]);
            }
        }
        return response(['success' => true, 'items' => MenuItem::with('images')->get()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(MenuItemPost $request, MenuItem $menuItem)
    {
        $this->authorize('manage', $menuItem);
        if ($menuItem->update($request->validated())) {
            return response(['success' => true, 'items' => MenuItem::all()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('manage', $menuItem);
        
        $dir = '/public/images/';
        foreach ($menuItem->images as $imageModel) {
            deleteImageFromDisk('public', $imageModel->image);
        }
        if ($menuItem->delete()) {
            return response(['success' => true], 200);
            //TODO: Delete MenuItemImages
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request $request) {
        MenuItemImage::where([
            'menu_item_id' => $request['menu_item_id'],
            'image' => $request['image'],
        ])->delete();
        deleteImageFromDisk('public', $request['image']);
        return response(['success' => true], 200);
    }
}
