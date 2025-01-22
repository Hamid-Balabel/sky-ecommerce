<?php

namespace App\Services\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function __;

class LoginService
{
    private string $model;

    private string $guard;

    /**
     * @throws InvalidEmailException
     */
    public function attempt(Request $request): array
    {
        $user = $this->getModel()
            ->query()
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new InvalidEmailException(__('api.invalid_email_and_password'), ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return [
            'user' => $user,
            'token' => $user->createToken($this->getGuard())->plainTextToken
        ];
    }

    /**
     * @param String $model
     * @return $this
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new $this->model();
    }

    /**
     * @param String $guard
     * @return $this
     */
    public function setGuard(string $guard): self
    {
        $this->guard = $guard;
        return $this;
    }

    /**
     * @return String
     */
    public function getGuard(): string
    {
        return $this->guard;
    }

    /**
     * @param  $user
     * @return bool
     */
    public function setLastLogin($user): bool
    {
        $user->last_login = now();
        $user->save();

        return true;
    }
}
