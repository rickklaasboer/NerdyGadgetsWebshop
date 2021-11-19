<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Entities\User;
use App\Util\Validation\Rules\Email;
use App\Util\Validation\Rules\Max;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function view()
    {
        if (auth()->isLoggedIn()) {
            return response()->redirect('/profile');
        }

        return response()->twig('auth/login.twig', [
            'errors' => $this->request->getSession()->getFlashBag()->get('errors')[0] ?? null,
        ]);
    }

    public function handle()
    {
        $form = [
            'email' => $this->request->get('email'),
            'password' => $this->request->get('password'),
        ];

        $validator = new Validator($form, [
            'email' => [new Required(), new Min(1), new Max(255), new Email()],
            'password' => [new Required(), new Min(8), new Max(255)],
        ]);

        if ($validator->fails()) {
            $this->request->getSession()
                ->getFlashBag()
                ->add('errors', $validator->messages());

            return response()->redirect(url()->prev());
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
            $this->request->getSession()
                ->getFlashBag()
                ->add('errors', ['Cannot find a user with provided credentials']);

            return response()->redirect(url()->prev());
        }

        $this->request->getSession()->set('user', [
            'id' => $user->getId(),
            'validUntil' => Carbon::now()->addDay()
        ]);

        return response()->redirect('/profile');
    }
}