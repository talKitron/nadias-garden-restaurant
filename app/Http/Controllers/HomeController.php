<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::orderBy('display_order')->get();
        return view('home',[
            'categories' => $categories,
        ]);
    }

    /**
     * Show the menu items by category and display order
     */
    public function menu() {
        // DB::listen(function($query){
        //     var_dump($query->sql);
        // });

        // $categories = DB::table('categories')
        //     ->select('menu_items.*')
        //     ->join('menu_items', 'categories.id', '=', 'menu_items.category_id')
        //     ->orderBy('display_order')
        //     ->get();
        $categories = Category::with('menuItems')
                        ->orderBy('display_order')
                        ->get();
        // die();
        return view('menu', [
            'categories' => $categories
        ]);
    }
}
