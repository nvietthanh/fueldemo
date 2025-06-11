<?php

namespace Helpers;

use Fuel\Core\Input;
use Fuel\Core\Pagination;
use Fuel\Core\Uri;

class PaginationHelper
{
    public static function paginate(int $total, int $per_page = 10, string $uri_segment = 'page')
    {
        $pagination_url = Uri::current();
        $query_params = Input::get();

        unset($query_params['page']);

        foreach ($query_params as $key => $value) {
            $pagination_url = self::add_query_param($pagination_url, $key, $value);
        }

        Pagination::set_config([
            'pagination_url' => $pagination_url,
            'total_items'    => $total,
            'per_page'       => $per_page,
            'uri_segment'    => $uri_segment,
        ]);

        $offset = Pagination::get('offset');
        $per_page = Pagination::get('per_page');

        $from = $offset + 1;
        $to = min($offset + $per_page, $total);

        $customPaginate = [
            'from' => $total > 0 ? $from : 0,
            'to' => $total > 0 ? $to : 0,
            'total' => $total,
        ];

        return array_merge($customPaginate ?? [], [
            'offset' => $offset,
            'per_page' => $per_page,
            'links' => Pagination::create_links(),
        ]);
    }

    private static function add_query_param($url, $param, $value)
    {
        $separator = strpos($url, '?') === false ? '?' : '&';
        $url .= $separator . urlencode($param) . '=' . urlencode($value);

        return $url;
    }
}
