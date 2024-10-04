<?php

namespace App\Http\Controllers\User_log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User_Log\UserLogCategory;

class LogCategoryController extends Controller
{
    public function index()
    {
        $categories = UserLogCategory::all();
        return view('log_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('log_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        UserLogCategory::create($request->all());

        return redirect()->route('log-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(UserLogCategory $logCategory)
    {
        return view('log_categories.edit', compact('logCategory'));
    }

    public function update(Request $request, UserLogCategory $logCategory)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $logCategory->update($request->all());

        return redirect()->route('log-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(UserLogCategory $logCategory)
    {
        $logCategory->delete();

        return redirect()->route('log-categories.index')->with('success', 'Category deleted successfully.');
    }
}
