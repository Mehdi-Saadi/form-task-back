<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Store or Update new resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'date' => ['required', 'date'],
            'photos' => ['required', 'array'],
        ]);

        $firstItem = Entry::first('*');

        if ($firstItem) {
            $firstItem->update($validated);
        } else {
            $firstItem = Entry::create($validated);
        }

        return $firstItem;
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $file = $request->file('photo');
        $pictureName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('entry'), $pictureName);
        $path = $file->path();

        return response()->json([
            'name' => $pictureName,
            'url' => "/entry/$pictureName",
            'path' => $path,
        ]);
    }

    public function destroyPhoto(Request $request): Response
    {
        $pathToDelete = $request->input('path');

        if (Storage::disk('public')->exists($pathToDelete)) {
            Storage::disk('public')->delete($pathToDelete);
        }

        return response()->noContent();
    }
}
