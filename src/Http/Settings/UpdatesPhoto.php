<?php

namespace Otinsoft\Toolkit\Http\Settings;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

trait UpdatesPhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validatePhoto($request);

        $photo = $this->savePhoto($request->photo);

        $oldPhoto = $request->user()->photo;
        if (! empty($oldPhoto)) {
            $this->disk()->delete($oldPhoto);
        }

        return tap($request->user(), function ($user) use ($photo) {
            $user->update(compact('photo'));
        });
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validatePhoto(Request $request)
    {
        $size = config('toolkit.photo_max_filesize');

        $this->validate($request, [
            'photo' => "required|max:$size|mimes:jpeg,png,gif"
        ]);
    }

    /**
     * Delete the user's profile photo.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $photo = $request->user()->photo;

        if (! empty($photo)) {
            $this->disk()->delete($photo);
        }

        $request->user()->update(['photo' => null]);
    }

    /**
     * Get the disk instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function disk()
    {
        return Storage::disk('photos');
    }

    /**
     * Save the new photo to the disk and return it's name.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected function savePhoto($file): string
    {
        $image = $this->resizeImage($file->path());

        $path = strtolower(Str::random(10).'.jpg');

        $this->disk()->put($path, $image);

        return basename($path);
    }

    /**
     * Resize and return the encoded image data.
     *
     * @param  string $path
     * @return string
     */
    protected function resizeImage(string $path): string
    {
        $image = (new ImageManager)->make($path)->orientate();

        $size = config('toolkit.photo_size');

        if ($image->width() !== $size || $image->height() !== $size) {
            $image = $image->fit($size);
        }

        return $image->encode('jpg');
    }
}
