<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use App\Http\Controllers\Controller;
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
    public function view()
    {
        if (auth()->isLoggedIn()) {
            return response()->redirect('/profile');
        }

        return response()->twig('auth/register.twig', [
            'errors' => $this->request->getSession()->getFlashBag()->get('errors')[0] ?? null,
        ]);
    }

    public function handle()
    {
        $form = [
            'first_name' => $this->request->get('first_name'),
            'last_name' => $this->request->get('last_name'),
            'email' => $this->request->get('email'),
            'password' => $this->request->get('password'),
            'password_confirm' => $this->request->get('password_confirm'),
        ];

        $validator = new Validator($form, [
            'first_name' => [new Required(), new Min(1), new Max(255)],
            'last_name' => [new Required(), new Min(1), new Max(255)],
            'email' => [new Required(), new Min(3), new Max(255), new Email(), new Unique(User::class, 'email')],
            'password' => [new Required(), new Min(8), new Max(255)],
            'password_confirm' => [new Required(), new Min(8), new Max(255), new Equals('password')],
        ]);

        if ($validator->fails()) {
            $this->request->getSession()
                ->getFlashBag()
                ->add('errors', $validator->messages());

            return response()->redirect(url()->prev());
        }

        $form = $this->prepareForm($form);

        $user = new User();
        $user->setFirstName($form['first_name']);
        $user->setLastName($form['last_name']);
        $user->setFullName("{$form['first_name']} {$form['last_name']}");
        $user->setEmail($form['email']);
        $user->setPassword($form['password']);
        $user->setCreatedAt(Carbon::now());

        $this->manager->persist($user);
        $this->manager->flush();

        return response()->redirect('/login');
    }

    /**
     * Prepare form for insertion
     *
     * @param array $form
     * @return array
     */
    private function prepareForm(array $form)
    {
        $form['password'] = password_hash($form['password'], PASSWORD_BCRYPT);

        return $form;
    }
}