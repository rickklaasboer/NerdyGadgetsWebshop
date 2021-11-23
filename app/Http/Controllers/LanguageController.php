<?php

namespace App\Http\Controllers;

use App\Translation\Translation;
use App\Util\Request;
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
    public function change(Request $request)
    {
        $session = $request->session();

        if (!$session->get('lang')) {
            $session->set('lang', env('APP_LANGUAGE'));
        }

        $current = $session->get('lang');
        $new = $current === Translation::LANGUAGE_EN ? Translation::LANGUAGE_NL : Translation::LANGUAGE_EN;

        $session->set('lang', $new);

        return response()->redirect(url()->prev());
    }
}