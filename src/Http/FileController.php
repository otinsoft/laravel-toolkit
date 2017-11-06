<?php

namespace Otinsoft\Toolkit\Http;

use Illuminate\Http\Request;
use Otinsoft\Toolkit\Files\File;

class FileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateStore($request);

        $this->authorize('create', File::class);

        $path = $request->file->store('images', $this->disk());

        return File::create([
            'path' => $path,
            'disk' => $this->disk(),
            'type' => File::IMAGE,
            'user_id' => $this->user()->id,
            'size' => $request->file->getSize(),
            'mimetype' => $request->file->getMimeType(),
            'name' => pathinfo($request->file->getClientOriginalName())['filename'],
            'uuid' => $request->uuid ?? null,
            // 'width' => $image->width(),
            // 'height' => $image->height(),
            // 'thumbnail' => 'thumbnails/'.Str::random().'.jpg',
        ])->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        $file->delete();
    }

    protected function validateStore(Request $request)
    {
        $maxSize = config('cfr.file_max_size');

        $this->validate($request, [
            'file' => "required|max:$maxSize|mimes:jpeg,png,gif,svg",
            'uuid' => 'nullable|unique:files',
        ]);
    }

    protected function disk(): string
    {
        return config('toolkit.files_disk');
    }
}
