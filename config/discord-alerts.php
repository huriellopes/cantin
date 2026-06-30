<?php

declare(strict_types=1);

use Spatie\DiscordAlerts\Jobs\SendToDiscordChannelJob;

return [
    /*
     * The webhook URLs that we'll use to send a message to Discord.
     */
    'webhook_urls' => [
        'default' => env('DISCORD_ALERT_WEBHOOK', 'https://discord.com/api/webhooks/1295907421788573717/llBrQ6hwxvyiWgz50gBuDvEoDCmyCIG7Zl_R97MeaYHeQ5vN8YM7ClizDl5EUbZndvHm'),
    ],

    /*
     * This job will send the message to Discord. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => SendToDiscordChannelJob::class,
];
