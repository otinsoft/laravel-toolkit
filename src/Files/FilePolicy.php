<?php

namespace Otinsoft\Toolkit\Files;

use Otinsoft\Toolkit\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the file.
     *
     * @param  \Otinsoft\Toolkit\Users\User $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function view(User $user, File $file)
    {
        return $file->user->is($user);
    }

    /**
     * Determine whether the user can create files.
     *
     * @param  \Otinsoft\Toolkit\Users\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the file.
     *
     * @param  \Otinsoft\Toolkit\Users\User $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function update(User $user, File $file)
    {
        return $file->user->is($user);
    }

    /**
     * Determine whether the user can delete the file.
     *
     * @param  \Otinsoft\Toolkit\Users\User $user
     * @param  \Otinsoft\Toolkit\Files\File $file
     * @return mixed
     */
    public function delete(User $user, File $file)
    {
        return $file->user->is($user);
    }
}
