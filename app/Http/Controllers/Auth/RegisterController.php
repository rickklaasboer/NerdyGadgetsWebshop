<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Util\Request;
use App\Util\Validation\Rules\Email;
use App\Util\Validation\Rules\Equals;
use App\Util\Validation\Rules\Max;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Rules\Unique;
use App\Util\Validation\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * View the register form
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

        return response()->twig('auth/register.twig');
    }

    /**
     * Handle incoming register request
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle(Request $request)
    {
        $form = $request->only('first_name', 'last_name', 'email', 'password', 'password_confirm');

        $validator = new Validator($form, [
            'first_name' => [new Required(), new Min(1), new Max(255)],
            'last_name' => [new Required(), new Min(1), new Max(255)],
            'email' => [new Required(), new Min(3), new Max(255), new Email(), new Unique(User::class, 'email')],
            'password' => [new Required(), new Min(8), new Max(255)],
            'password_confirm' => [new Required(), new Min(8), new Max(255), new Equals('password')],
        ]);

        if ($validator->fails()) {
            return response()->flash('errors', $validator->messages())
                ->redirect(url()->prev());
        }

        $user = new User();
        $user->fill([
            'FirstName' => $form['first_name'],
            'LastName' => $form['last_name'],
            'FullName' => "{$form['first_name']} {$form['last_name']}",
            'Email' => $form['email'],
            'Password' => password_hash($form['password'], PASSWORD_BCRYPT),
            'CreatedAt' => Carbon::now(),
        ]);

        $this->manager->persist($user);
        $this->manager->flush();

        return response()->redirect('/login');
    }
}