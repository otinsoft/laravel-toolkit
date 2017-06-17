<?php

namespace Otinsoft\Toolkit\Database;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use Concerns\DeleteOrFail,
        Concerns\SerializeDate;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
