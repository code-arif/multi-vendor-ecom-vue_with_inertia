<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    //======================get section, category, subcategory list for header===================//
    public function getHeaderSection()
    {
        $sections = Section::where('status', 1)
            ->with([
                'categories' => function ($query) {
                    $query->orderBy('name', 'asc'); // Categories are also ordered alphabetically
                },
            ])
            ->orderBy('sec_name', 'asc') // Ordering sections alphabetically by name
            ->get();

        return response()->json($sections);
    }

    //===========================show main home page =============================//
    public function home()
    {
        // return $sections;dd();
        return Inertia::render('User/Home/HomePage', []);
    }
}
