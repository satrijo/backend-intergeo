<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        
        // Generate unique filename
        $filename = Str::uuid() . '.' . $extension;
        
        // Store file
        $path = $file->storeAs('media/' . date('Y/m'), $filename, 'public');
        
        // Create media file record
        $mediaFile = MediaFile::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'size' => $size,
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'disk' => 'public',
            'metadata' => [
                'uploaded_at' => now()->toISOString(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'file' => [
                'id' => $mediaFile->id,
                'name' => $originalName,
                'url' => $mediaFile->full_url,
                'size' => $mediaFile->human_size,
                'type' => $mimeType,
            ],
        ]);
    }

    public function index()
    {
        $files = MediaFile::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($files);
    }

    public function destroy(MediaFile $mediaFile)
    {
        $mediaFile->delete();

        return response()->json(['success' => true]);
    }
} 