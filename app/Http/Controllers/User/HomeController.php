<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductSlider;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //======================get section, category, subcategory list for header===================//
    public function getHeaderSection()
    {
        $sections = Section::where('status', 1)
            ->with([
                'categories' => function ($query) {
                    $query->orderBy('name', 'asc');
                },
            ])
            ->orderBy('sec_name', 'asc')
            ->get();

        return response()->json($sections);
    }

    //===========================show main home page =============================//
    public function home()
    {
        //get latest 8 category
        $categories = Category::where('status', 1)
            ->latest()
            ->select('id', 'name', 'image', 'url')
            ->withCount('products')
            ->with('products:id,category_id,product_name')
            ->take(8)
            ->get();

        //get latest 8 product
        $products = Product::where('status', 1)->latest()->select('id', 'product_name', 'image', 'price')->with('specifications')->take(8)->get();

        //get latest 8 feature product
        $featured_products = Product::where('is_featured', 1)->where('status', 1)->latest()->select('id', 'product_name', 'image', 'price')->with('specifications')->take(8)->get();
        // return $products;dd();

        $slider = ProductSlider::where('status', 1)->with('product')->get();
        return Inertia::render('User/Home/HomePage', [
            'products' => $products,
            'slider' => $slider,
            'categories' => $categories,
            'featured_products' => $featured_products,
        ]);
    }
}
