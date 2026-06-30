<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    config([
        'services.telegram.token' => 'TEST-TOKEN',
        'services.telegram.chat' => '-1003346127818',
        'services.telegram.thread_alerts' => '951',
        'logging.channels.telegram_alerts.thread' => '951',
    ]);
});

it('sends an alert to the telegram forum topic with the thread id', function () {
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

    Log::channel('telegram_alerts')->error('Falha de teste', [
        'endpoint' => 'GET /admin',
        'exception' => 'RuntimeException',
    ]);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/botTEST-TOKEN/sendMessage')
            && $request['chat_id'] === '-1003346127818'
            && (int) $request['message_thread_id'] === 951
            && $request['parse_mode'] === 'HTML'
            && str_contains($request['text'], 'Falha de teste');
    });
});

it('does not send when no token is configured', function () {
    config(['services.telegram.token' => null]);
    Http::fake();

    Log::channel('telegram_alerts')->error('Sem token, sem envio');

    Http::assertNothingSent();
});
