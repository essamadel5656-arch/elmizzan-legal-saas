<?php

namespace App\Http\Controllers;

use App\Models\Jurisdiction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JurisdictionController extends Controller
{
    public function index()
    {
        $jurisdictions = Jurisdiction::with('courts')->get();
        return view('jurisdictions.index', compact('jurisdictions'));
    }

    public function create()
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');
        return view('jurisdictions.create');
    }

    public function store(Request $request)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $request->validate([
            'name' => 'required|string|max:255|unique:jurisdictions,name',
        ]);

        Jurisdiction::create(['name' => $request->name]);

        return redirect()->route('jurisdictions.index')
                         ->with('success', 'تم إضافة نوع القضاء بنجاح!');
    }

    public function show(Jurisdiction $jurisdiction)
    {
        return view('jurisdictions.show', compact('jurisdiction'));
    }

    public function edit(Jurisdiction $jurisdiction)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');
        return view('jurisdictions.edit', compact('jurisdiction'));
    }

    public function update(Request $request, Jurisdiction $jurisdiction)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $jurisdiction->update(['name' => $request->name]);

        return redirect()->route('jurisdictions.index')
                         ->with('success', 'تم تعديل نوع القضاء بنجاح!');
    }

    public function destroy(Jurisdiction $jurisdiction)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $jurisdiction->delete();
        return redirect()->route('jurisdictions.index')
                         ->with('success', 'تم حذف نوع القضاء بنجاح!');
    }
}