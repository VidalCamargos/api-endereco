<?php

namespace App\Saloon\Address;


use App\Http\Middleware\GuzzleTracingMiddleware;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class AddressConnector extends Connector
{
    use AlwaysThrowOnErrors;

    public function __construct()
    {
        $this->sender()->getHandlerStack()->push(GuzzleTracingMiddleware::trace());

//        $this->middleware()->onResponse(new ResponseLogger());
    }

    public function resolveBaseUrl(): string
    {
        return '';
    }
}
