<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::query()
            ->with(['nutritions']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $foods = $query->latest()->paginate(16)->withQueryString();

        return view('foods', [
            'foods' => $foods,
        ]);
    }
}
