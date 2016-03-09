<?php

namespace Helper;

/**
 * Class EndpointTest
 *
 * Extends this class for ease of test writing.
 */
class EndpointTest
{
    /**
     * API version of the endpoint;
     *
     * @var string
     */
    protected $version;

    /**
     * Enpoint name.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Pagination items per page.
     *
     * @var int
     */
    protected $items_per_page = 10;

    public function __construct($file = __FILE__)
    {
        $this->version = strtolower(basename(dirname($file)));
        $full_path = explode('\\', get_called_class());
        $this->endpoint = '/' . $this->version . '/' . strtolower(str_replace('Cest', '', end($full_path)));
    }
}