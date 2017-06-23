<?php

namespace Otinsoft\Toolkit\Http;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Otinsoft\Toolkit\JavaScript\ScriptVariables;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function user()
    {
        return Auth::user();
    }

    /**
     * Inject script variables.
     *
     * @param  array|string|\Closure $key
     * @param  mixed $value
     * @return void
     */
    protected function scriptVariables($key, $value = null)
    {
        ScriptVariables::add($key, $value);
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
