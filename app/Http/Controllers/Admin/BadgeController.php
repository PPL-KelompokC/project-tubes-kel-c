<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    // LIST BADGE 
    public function index()
    {
        $badges = Badge::latest()->get()->groupBy('level');

        return view('admin.badges.index', compact('badges'));
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.badges.create');
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $icon = null;

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('badges', 'public');
        }

        Badge::create([
            'name' => $request->name,
            'category' => $request->category,
            'level' => $request->level,
            'description' => $request->description,
            'icon' => $icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.badges.index')
                         ->with('success', 'Badge berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit($id)
    {
        $badge = Badge::findOrFail($id);
        return view('admin.badges.edit', compact('badge'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        // upload icon baru (kalau ada)
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('badges', 'public');
            $badge->icon = $icon;
        }

        $badge->update([
            'name' => $request->name,
            'category' => $request->category,
            'level' => $request->level,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.badges.index')
                         ->with('success', 'Badge berhasil diupdate!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return redirect()->route('admin.badges.index')
                         ->with('success', 'Badge berhasil dihapus!');
    }
}