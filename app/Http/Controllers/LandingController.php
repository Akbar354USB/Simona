<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function landing()
    {
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload'
        ])->get();
        return view('landing',  compact('categories'));
    }
}
