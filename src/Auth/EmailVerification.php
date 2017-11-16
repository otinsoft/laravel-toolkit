<?php

namespace Otinsoft\Toolkit\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait EmailVerification
{
    /**
     * Send verification link email.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendVerificationLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->verificationRepository()->sendVerificationLink(
            $request->only('email')
        );

        return $response === VerificationRepository::LINK_SENT
            ? $this->sendVerificationLinkResponse($request, $response)
            : $this->sendVerificationLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful verification link.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendVerificationLinkResponse(Request $request, $response)
    {
        if ($request->expectsJson()) {
            return ['status' => trans($response)];
        }

        return back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed verification link.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendVerificationLinkFailedResponse(Request $request, $response)
    {
        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }

    /**
     * Verify email.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $token
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, $token)
    {
        $response = $this->verificationRepository()->verify($token);

        return is_object($response)
            ? $this->sendVerificationResponse($request, $response)
            : $this->sendVerificationFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful verification.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return mixed
     */
    abstract protected function sendVerificationResponse(Request $request, $user);

    /**
     * Get the response for a failed verification.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendVerificationFailedResponse(Request $request, $response)
    {
        return response()->json(['error' => trans($response)], 400);
    }

    /**
     * @return \Otinsoft\Toolkit\Auth\VerificationRepository
     */
    protected function verificationRepository()
    {
        return app(VerificationRepository::class);
    }
}
