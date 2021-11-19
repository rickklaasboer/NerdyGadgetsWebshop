<?php

namespace App\Util;

/**
 * Class Paginator
 *
 * @package App\Util
 * @author Rick Klaasboer <rick@klaasboer.org>
 */
class Paginator
{
    /**
     * Total items in collection
     *
     * @var int
     */
    protected int $total_items;

    /**
     * Items that are displayed per page
     *
     * @var int
     */
    protected int $items_per_page;

    /**
     * The current page
     *
     * @var int
     */
    protected int $current_page;

    /**
     * The amount of pages to show
     *
     * @var int
     */
    protected int $max_pages_to_show = 8;

    /**
     * Paginatior constructor.
     *
     * @param $total_items
     * @param $items_per_page
     * @param $current_page
     * @param int $max_pages_to_show
     */
    public function __construct($total_items, $items_per_page, $current_page, $max_pages_to_show = 8)
    {
        $this->total_items = $total_items;
        $this->items_per_page = $items_per_page;
        $this->current_page = $current_page;
        $this->max_pages_to_show = $max_pages_to_show;
    }

    /**
     * Construct of self
     *
     * @param $total_items
     * @param $items_per_page
     * @param $current_page
     * @param int $max_pages_to_show
     * @return Paginator
     */
    public static function make($total_items, $items_per_page, $current_page, $max_pages_to_show = 8)
    {
        return new self($total_items, $items_per_page, $current_page, $max_pages_to_show = 8);
    }

    protected function paramsExcept($params = [], $except = [])
    {
        $bag = [];

        foreach ($params as $key => $value) {
            if (!in_array($key, $except)) {
                $bag[$key] = $value;
            }
        }

        return $bag;
    }

    /**
     * Get query params with exceptions
     *
     * @param array $except
     * @param null $query
     * @return array
     */
    protected function getQueryParamsExcept($except = [], $query = null)
    {
        if (is_null($query)) {
            $query = $_GET;
        }

        return $this->paramsExcept($query, $except);
    }

    /**
     * Get the current url
     *
     * @return string
     */
    protected function getCurrentUrl()
    {
        return strtok($_SERVER["REQUEST_URI"], '?');
    }

    /**
     * Generate a query string
     *
     * @param array $parameters
     * @return string
     */
    protected function generateQueryString($parameters = [])
    {
        return http_build_query($parameters);
    }

    /**
     * Generate a page URL
     *
     * @param $page_number
     * @return string
     */
    protected function getPageUrl($page_number)
    {
        $params = $this->getQueryParamsExcept(['page']);
        $params['page'] = $page_number;
        $query_string = $this->generateQueryString($params);
        $url = $this->getCurrentUrl();

        return "$url?$query_string";
    }

    /**
     * Get the number of total pages
     *
     * @return false|float
     */
    protected function getTotalPages()
    {
        return ceil($this->total_items / $this->items_per_page);
    }

    /**
     * Get the current page
     *
     * @return int
     */
    protected function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * Get the amount of pages to show
     *
     * @return int|mixed
     */
    protected function getMaxPagesToShow()
    {
        return $this->max_pages_to_show;
    }

    /**
     * Get next page, returns null when there is none
     *
     * @return int|null
     */
    protected function getNextPage()
    {
        if ($this->current_page < $this->getTotalPages()) {
            return $this->current_page + 1;
        }

        return null;
    }

    /**
     * Get previous page, returns null when there is none
     *
     * @return int|null
     */
    protected function getPreviousPage()
    {
        if ($this->current_page > 1) {
            return $this->current_page - 1;
        }

        return null;
    }

    /**
     * Generate the next page url
     *
     * @return string|null
     */
    protected function getNextUrl()
    {
        $next_page = $this->getNextPage();

        if (!is_null($next_page)) {
            return $this->getPageUrl($next_page);
        }

        return null;
    }

    /**
     * Generate the previous page url
     *
     * @return string|null
     */
    protected function getPreviousUrl()
    {
        $prev_page = $this->getPreviousPage();

        if (!is_null($prev_page)) {
            return $this->getPageUrl($prev_page);
        }

        return null;
    }

    /**
     * Get pages
     *
     * @return array
     */
    protected function getPages()
    {
        $pages = [];
        $sliding_range = ceil($this->getMaxPagesToShow() / 2);

        if ($this->getTotalPages() > $this->getMaxPagesToShow()) {
            for ($i = $this->getCurrentPage(); $i < $sliding_range + $this->getCurrentPage(); $i++) {
                if ($i >= $this->getTotalPages()) break;
                if (in_array($i, range($this->getTotalPages() - $sliding_range + 1, $this->getTotalPages()))) continue;

                $pages[] = [
                    'number' => $i,
                    'url' => $this->getPageUrl($i),
                    'is_active' => $this->getActivePage() === $i,
                    'is_disabled' => false,
                ];
            }

            $pages[] = [
                'number' => '...',
                'url' => null,
                'is_active' => false,
                'is_disabled' => true,
            ];

            $next_pages = [];
            for ($i = $this->getTotalPages(); $i > $this->getTotalPages() - $sliding_range; $i--) {
                $next_pages[] = [
                    'number' => $i,
                    'url' => $this->getPageUrl($i),
                    'is_active' => $this->getActivePage() === (int) $i,
                    'is_disabled' => false,
                ];
            }

            $pages = array_merge($pages, array_reverse($next_pages));

        } else {
            for ($i = 1; $i <= $this->getTotalPages(); $i++) {
                $pages[] = [
                    'number' => $i,
                    'url' => $this->getPageUrl($i),
                    'is_active' => $this->getActivePage() === $i,
                    'is_disabled' => false,
                ];
            }
        }

        return $pages;
    }

    /**
     * Get the active page
     *
     * @return int
     */
    protected function getActivePage()
    {
        return $this->current_page;
    }

    /**
     * Generate the paginator html
     *
     * @return string
     */
    protected function toHtml()
    {
        $html = '';

        $html .= "<ul class=\"pagination\">";

        $first_url = $this->getPageUrl(1);
        $html .= "<li class=\"page-item\"><a class=\"page-link bg-light\" href=\"$first_url\">First</a></li>";

        if ($this->getPreviousPage()) {
            $prev_url = $this->getPreviousUrl();
            $html .= "<li class=\"page-item\"><a class=\"page-link bg-light\" href=\"$prev_url\">Previous</a></li>";
        } else {
            $html .= "<li class=\"page-item disabled\"><a class=\"page-link bg-light\" href=\"#\">Previous</a></li>";
        }

        foreach ($this->getPages() as $page) {
            $number = $page['number'];
            $url = $page['url'];
            $is_active = $page['is_active'];
            $is_disabled = $page['is_disabled'];

            if ($is_active) {
                $html .= "<li class=\"page-item active\"><a class=\"page-link\" href=\"$url\">$number</a></li>";
            } elseif ($is_disabled) {
                $html .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">$number</a></li>";
            } else {
                $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"$url\">$number</a></li>";
            }
        }

        if ($this->getNextPage()) {
            $next_url = $this->getNextUrl();
            $html .= "<li class=\"page-item\"><a class=\"page-link bg-light\" href=\"$next_url\">Next</a></li>";
        } else {
            $html .= "<li class=\"page-item disabled\"><a class=\"page-link bg-light\" href=\"#\">Next</a></li>";
        }

        $last_url = $this->getPageUrl($this->getTotalPages());
        $html .= "<li class=\"page-item\"><a class=\"page-link bg-light\" href=\"$last_url\">Last</a></li>";

        $html .= "</ul>";

        return $html;
    }

    /**
     * Intercept echo and forward it
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
}