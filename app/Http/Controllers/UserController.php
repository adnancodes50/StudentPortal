<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use App\Models\Category;
use App\Models\Flight;


class UserController extends Controller
{
    public function index()
{
    // Get only active categories – adjust if you don't need status filtering
    $categories = Category::where('status', 'active')->get();
    // Or get all: $categories = Category::all();

    return view('frontend.home', compact('categories'));
}

public function show($id)
{
    $category = Category::with(['flights'])->findOrFail($id);
    return view('frontend.category', compact('category'));
}

    public function getUsers()
    {
        // ✅ Get all users (you can filter if needed)
        $users = User::all();

        return view('admin.passengers.index', compact('users'));
    }
}
