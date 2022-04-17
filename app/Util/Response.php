<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Twig\Environment;

class Response
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Return a Twig response
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return SymfonyResponse
     */
    public function view(string $content, int $status = 200, array $headers = []): SymfonyResponse
    {
        return new SymfonyResponse($content, $status, $headers);
    }

    /**
     * Return a Twig response
     *
     * @param string $template
     * @param array $context
     * @return SymfonyResponse
     */
    public function twig(string $template, array $context = []): SymfonyResponse
    {
        $twig = app(Environment::class);
        return $this->view($twig->render($template, array_merge($context, [
            // Attach error object to every twig response
            'errors' => $this->request->getSession()->getFlashBag()->get('errors')[0] ?? null
        ])));
    }

    /**
     * Return a file response
     *
     * @param $file
     * @param int $status
     * @param array $headers
     * @param bool $public
     * @param string|null $contentDisposition
     * @param bool $autoEtag
     * @param bool $autoLastModified
     * @return BinaryFileResponse
     */
    public function file($file, int $status = 200, array $headers = [], bool $public = true, string $contentDisposition = null, bool $autoEtag = false, bool $autoLastModified = true): BinaryFileResponse
    {
        return new BinaryFileResponse($file, $status, $headers, $public, $contentDisposition, $autoEtag, $autoLastModified);
    }

    /**
     * Return a JSON response
     *
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    public function json($data = null, int $status = 200, array $headers = [], bool $json = true): JsonResponse
    {
        return new JsonResponse(json_encode($data), $status, $headers, $json);
    }

    /**
     * Return a redirect response
     *
     * @param string $url
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirect(string $url, int $status = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Utility function for adding something to response flash bag
     *
     * @param $type
     * @param $message
     * @return $this
     */
    public function flash($type, $message)
    {
        $this->request->getSession()->getFlashBag()->add($type, $message);

        return $this;
    }
}