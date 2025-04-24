<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display initial resource.
     */
    public function index(): ?Entry
    {
        return Entry::first('*');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store('entries', 'public');

        return response()->json([
            'url' => asset("storage/$path"),
            'path' => $path,
        ]);
    }

    public function destroyPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store('entries', 'public');

        return response()->json([
            'url' => asset("storage/$path"),
            'path' => $path,
        ]);
    }
}
