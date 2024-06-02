<?php

namespace App\Http\Middleware;

use GuzzleHttp\Psr7\Uri;
use Saloon\Contracts\ResponseMiddleware;
use Saloon\Http\Response;
use Sentry\Breadcrumb;
use Sentry\SentrySdk;
use Sentry\Tracing\SpanContext;
use function Sentry\startTransaction;

class ResponseLogger implements ResponseMiddleware
{
    public function __invoke(Response $response)
    {
        $request = $response->getPendingRequest();
        $requestUri = $request->getUri();
        $partialUri = Uri::fromParts([
            'scheme' => $requestUri->getScheme(),
            'host' => $requestUri->getHost(),
            'port' => $requestUri->getPort(),
            'path' => $requestUri->getPath(),
        ]);

        $transactionContext = \Sentry\Tracing\TransactionContext::make()
            ->setName($requestUri->getPath())
            ->setOp('http.server');

        $transaction = startTransaction($transactionContext);

        SentrySdk::getCurrentHub()->setSpan($transaction);

        $spanContext = SpanContext::make()
            ->setOp('http.server');

        $spanContext->setDescription($request->getMethod()->value.' '.$partialUri);
        $spanContext->setData([
            'http.request.method' => $request->getMethod()->value,
            'http.query' => $request->query(),
            'http.fragment' => $requestUri->getFragment(),
        ]);

        $span1 = $transaction->startChild($spanContext);

        $span1->setStartTimestamp(now()->getTimestamp());
        SentrySdk::getCurrentHub()->setSpan($span1);
        $span1->setHttpStatus(401);

        $span1->finish();

        $breadcrumbData = [
            'url' => (string) $partialUri,
            'http.request.method' => $request->getMethod()->value,
            'http.request.body' => $request->body()?->all(),
            'http.query' => $request->query(),
            'http.fragment' => $requestUri->getFragment(),
            'http.response.status_code' => 401,
            'http.response.body' => $response->body(),
        ];

        SentrySdk::getCurrentHub()->addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_HTTP,
            'http',
            null,
            $breadcrumbData
        ));

        SentrySdk::getCurrentHub()->setSpan($transaction);

        $transaction->finish();
    }
}
