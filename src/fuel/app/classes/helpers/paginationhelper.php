<?php

namespace Helpers;

use Fuel\Core\Pagination;
use Fuel\Core\Uri;

class PaginationHelper
{
    public static function paginate(int $per_page = 10, ?int $total = null, string $uri_segment = 'page')
    {
        Pagination::set_config([
            'pagination_url' => Uri::current(),
            'total_items'    => $total,
            'per_page'       => $per_page,
            'uri_segment'    => $uri_segment,
        ]);

        if (!is_null($total)) {
            $offset = Pagination::get('offset');
            $limit = Pagination::get('per_page');
            $from = $offset + 1;
            $to = min($offset + $limit, $total);

            $customPaginate = [
                'from' => $total > 0 ? $from : 0,
                'to' => $total > 0 ? $to : 0,
                'total' => $total,
            ];
        }

        return array_merge($customPaginate ?? [], [
            'links' => Pagination::create_links(),
        ]);
    }
}
