<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntryController extends Controller
{
    /**
     * Display initial resource.
     */
    public function index(): JsonResponse
    {
        $entry = Entry::first('*');

        if ($entry) {
            $entry->photos = collect($entry->photos)->map(fn ($photo) => [
                'name' => $photo,
                'url' => "/entry/$photo",
            ]);
        }

        return response()->json($entry);
    }

    /**
     * Store or Update new resource in storage.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'date' => ['required', 'date'],
            'photos' => ['array'],
        ]);

        $firstItem = Entry::first('*');

        if ($firstItem) {
            $firstItem->update($validated);
        } else {
            Entry::create($validated);
        }

        return response()->noContent();
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $file = $request->file('photo');
        $pictureName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('entry'), $pictureName);

        return response()->json([
            'name' => $pictureName,
            'url' => "/entry/$pictureName",
        ]);
    }

    public function destroyPhoto(Request $request): Response
    {
        $pathToDelete = public_path('entry/'.$request->input('name'));

        if (File::exists($pathToDelete)) {
            File::delete($pathToDelete);
        }

        return response()->noContent();
    }
}
