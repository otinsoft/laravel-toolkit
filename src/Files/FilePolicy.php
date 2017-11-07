<?php

namespace Otinsoft\Toolkit\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the file.
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function view(Model $user, File $file)
    {
        return $file->user->is($user);
    }

    /**
     * Determine whether the user can create files.
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @return mixed
     */
    public function create(Model $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the file.
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function update(Model $user, File $file)
    {
        return $file->user->is($user);
    }

    /**
     * Determine whether the user can delete the file.
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function delete(Model $user, File $file)
    {
        return $file->user->is($user);
    }
}
