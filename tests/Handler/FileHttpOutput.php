<?php

namespace Fastwf\Tests\Handler;

use Fastwf\Api\Engine\Output\HttpOutputInterface;

class FileHttpOutput implements HttpOutputInterface {

    public $path;
    public $status = 0;
    public $headers = [];
    public $resource = null;

    public function __construct($path) {
        $this->path = $path;
    }

    public function sendStatus($status) {
        $this->status = $status;
    }

    public function sendHeader($header) {
        $this->headers[] = $header;
    }

    public function getResponseStream() {
        if ($this->resource === null) {
            $this->resource = \fopen($this->path, 'w');
        }

        return $this->resource;
    }

}