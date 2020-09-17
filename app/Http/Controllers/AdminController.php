<?php

namespace App\Http\Controllers;

use App\Category;
use App\MenuItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function menu(){
        $categories = Category::orderBy('display_order')->get();
        $menuItems = MenuItem::orderBy('category_id')->get();
        return view('admin.menu-editor', [
            'categories' => $categories,
            'items' => $menuItems,
        ]);
    }
}
