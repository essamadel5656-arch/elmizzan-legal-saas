<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtLevel;
use App\Models\Jurisdiction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['jurisdiction'])->get();
        return view('courts.index', compact('courts'));
    }

    public function create()
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $jurisdictions = Jurisdiction::all();
        return view('courts.create', compact('jurisdictions'));
    }

    public function store(Request $request)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $request->validate([
            'name'            => 'required|string|max:255',
            'jurisdiction_id' => 'required|exists:jurisdictions,id',
        ]);

        Court::create([
            'name'            => $request->name,
            'jurisdiction_id' => $request->jurisdiction_id,
        ]);

        return redirect()->route('courts.index')
                         ->with('success', 'تم إضافة المحكمة بنجاح!');
    }

    public function show(Court $court)
    {
        return view('courts.show', compact('court'));
    }

    public function edit(Court $court)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $jurisdictions = Jurisdiction::all();
        return view('courts.edit', compact('court', 'jurisdictions'));
    }

    public function update(Request $request, Court $court)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $request->validate([
            'name'            => 'required|string|max:255',
            'jurisdiction_id' => 'required|exists:jurisdictions,id',
        ]);

        $court->update([
            'name'            => $request->name,
            'jurisdiction_id' => $request->jurisdiction_id,
        ]);

        return redirect()->route('courts.index')
                         ->with('success', 'تم تعديل المحكمة بنجاح!');
    }

    public function destroy(Court $court)
    {
        // 🔒 Admin only
        Gate::authorize('manage-courts');

        $court->delete();
        return redirect()->route('courts.index')
                         ->with('success', 'تم حذف المحكمة بنجاح!');
    }
}