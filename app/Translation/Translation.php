<?php

namespace App\Translation;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Translation
 *
 * @package App\Translation
 * @author Rick Klaasboer <rick@klaasboer.org>
 */
class Translation
{
    const LANGUAGE_EN = 'en';
    const LANGUAGE_NL = 'nl';

    /**
     * @var mixed $language
     */
    protected mixed $language;

    /**
     * @var array $languageFile
     */
    protected array $languageFile;

    /**
     * @var Request $request
     */
    protected Request $request;

    /**
     * Translation constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->language = $request->getSession()->get('lang', env('APP_LANGUAGE', self::LANGUAGE_EN));

        // Include lang files
        $file = __DIR__ . '/../../resources/lang/' . $this->language . '.php';
        $fallback = __DIR__ . '/../../resources/lang/en.php';

        // Check if the provided file exists
        // If it does not, fallback to english.
        // If the english file is somehow missing, fallback to an empty array
        if (file_exists($file)) {
            $this->languageFile = require($file);
        } elseif (file_exists($fallback)) {
            $this->languageFile = require($fallback);
        } else {
            $this->languageFile = [];
        }
    }

    /**
     * Translate a string
     *
     * @param $key
     * @param array $replace
     * @param string|null $fallback
     * @return mixed
     */
    public function translate($key, array $replace = [], string $fallback = null): mixed
    {
        if (isset($this->languageFile[$key])) {
            $value = $this->languageFile[$key];

            if (is_array($replace)) {
                foreach ($replace as $identifier => $worth) {
                    $value = str_replace("%$identifier%", $worth, $value);
                }
            }

            return $value;
        }

        if (!is_null($fallback)) {
            return $fallback;
        }

        return $key;
    }
}