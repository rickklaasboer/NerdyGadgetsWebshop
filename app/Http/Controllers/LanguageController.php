<?php

namespace App\Http\Controllers;

use App\Translation\Translation;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Toggle website language
     *
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function change()
    {
        $session = $this->request->getSession();

        if (!$session->get('lang')) {
            $session->set('lang', env('APP_LANGUAGE'));
        }

        $current = $session->get('lang');

        $session->set(
            'lang',
            $current === Translation::LANGUAGE_EN ? Translation::LANGUAGE_NL : Translation::LANGUAGE_EN
        );

        return response()->redirect(url()->prev());
    }
}