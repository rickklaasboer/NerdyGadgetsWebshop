<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Entities\User;
use App\Util\Request;
use App\Util\Validation\Rules\Email;
use App\Util\Validation\Rules\Max;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * View the login form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function view()
    {
        if (auth()->isLoggedIn()) {
            return response()->redirect('/profile');
        }

        return response()->twig('auth/login.twig');
    }

    /**
     * Handle incoming login request
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle(Request $request)
    {
        $form = $request->only('email', 'password');

        $validator = new Validator($form, [
            'email' => [new Required(), new Min(1), new Max(255), new Email()],
            'password' => [new Required(), new Min(8), new Max(255)],
        ]);

        if ($validator->fails()) {
            return response()->flash('errors', $validator->messages())
                ->redirect(url()->prev());
        }

        /** @var User $user */
        $user = ($this->manager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where($this->manager->getExpressionBuilder()->eq('u.email', '?0'))
                ->setParameter(0, $form['email'])
                ->getQuery()
                ->getResult()[0]) ?? null;

        $passwordIsValid = password_verify($form['password'], $user?->getPassword());

        if (!$user || !$passwordIsValid) {
            return response()->flash('errors', ['Cannot find a user with provided credentials'])
                ->redirect(url()->prev());
        }

        $this->request->getSession()->set('user', [
            'id' => $user->getId(),
            'validUntil' => Carbon::now()->addDay()
        ]);

        return response()->redirect('/profile');
    }
}