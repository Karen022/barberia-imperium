<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index() {
        $featuredProducts = Product::where('is_featured', true)->take(3)->get();

        return view('landing.home', compact('featuredProducts'));
    }

    public function products() {
        $products = Product::orderBy('name')->get();
        
        return view('landing.products', compact('products'));
    }

    public function services() {
        $services = Service::orderBy('name')->get();

        return view('landing.services', compact('services'));
    }
}
