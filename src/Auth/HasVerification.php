<?php

namespace Otinsoft\Toolkit\Auth;

use Otinsoft\Toolkit\Notifications\Verification as VerificationNotification;

trait HasVerification
{
    /**
     * Determine if the user is verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return (bool) $this->verified;
    }

    /**
     * Verify the user.
     *
     * @return void
     */
    public function verify()
    {
        $this->verified = true;
        $this->save();
    }

    /**
     * Send the verification notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendVerificationNotification($token)
    {
        $this->notify(new VerificationNotification($token));
    }
}
