<?php

class Helpers_Pagination
{
    public static function paginate(int $total, string $base_url, int $per_page = 10, string $uri_segment = 'page')
    {
        Pagination::set_config([
            'pagination_url' => Uri::create($base_url),
            'total_items'    => $total,
            'per_page'       => $per_page,
            'uri_segment'    => $uri_segment,
        ]);

        $offset = Pagination::get('offset');
        $limit = Pagination::get('per_page');
        $from = $offset + 1;
        $to = min($offset + $limit, $total);

        return [
            'from' => $total > 0 ? $from : 0,
            'to' => $total > 0 ? $to : 0,
            'total' => $total,
            'links' => \Pagination::create_links(),
        ];
    }
}
