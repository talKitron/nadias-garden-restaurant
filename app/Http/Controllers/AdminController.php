<?php

namespace App\Http\Controllers;

use App\Category;
use App\MenuItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function menu(){
        $categories = Category::orderBy('display_order')->get();
        $menuItems = MenuItem::orderBy('category_id')->with('images')->get();
        // $menuItemsByCategory = [];
        // foreach ($menuItems as $item) {
        //     if (!array_key_exists($item->category_id, $menuItemsByCategory)) {
        //         $menuItemsByCategory[$item->category_id] = [];
        //     }
        //     array_push($menuItemsByCategory[$item->category_id], $item);
        // }
        // dd($menuItemsByCategory);
        // dd($menuItems);
        return view('admin.menu-editor', [
            'categories' => $categories,
            'items' => $menuItems,
            // 'itemsPerCategory' => $menuItemsByCategory,
        ]);
    }
}
