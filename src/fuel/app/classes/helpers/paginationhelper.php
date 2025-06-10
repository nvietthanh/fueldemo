<?php

namespace Helpers;

use Fuel\Core\Pagination;
use Fuel\Core\Uri;

class PaginationHelper
{
    public static function paginate(int $total, int $per_page = 10, string $uri_segment = 'page')
    {
        Pagination::set_config([
            'pagination_url' => Uri::current(),
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
}
