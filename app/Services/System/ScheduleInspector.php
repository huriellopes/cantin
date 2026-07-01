<?php

declare(strict_types=1);

namespace App\Services\System;

use Cron\CronExpression;
use DateTimeZone;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Descreve as tarefas agendadas (App\Console\Kernel::schedule) para inspeção
 * pelo super-admin: comando, expressão cron e próxima execução.
 */
class ScheduleInspector
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function events(): Collection
    {
        /** @var Schedule $schedule */
        $schedule = resolve(Schedule::class);

        return collect($schedule->events())
            ->map(fn (Event $event): array => $this->describe($event))
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function describe(Event $event): array
    {
        return [
            'command' => $this->label($event),
            'expression' => $event->expression,
            'human' => $this->humanize($event->expression),
            'description' => $event->description,
            'next_run' => $this->nextRun($event),
            'timezone' => $this->timezone($event),
        ];
    }

    /**
     * Fuso do evento como string; recai no fuso da aplicação quando ausente.
     */
    private function timezone(Event $event): string
    {
        $tz = $event->timezone;

        if ($tz instanceof DateTimeZone) {
            return $tz->getName();
        }

        return $tz !== '' ? $tz : (string) config('app.timezone');
    }

    private function label(Event $event): string
    {
        $command = $event->command ?? $event->getSummaryForDisplay();

        // Remove o binário do PHP e o "artisan" para exibir só o comando.
        $command = (string) preg_replace('/^.*artisan[\'"]?\s+/', '', (string) $command);

        return mb_trim($command) ?: $event->getSummaryForDisplay();
    }

    /**
     * Traduz as expressões cron mais comuns para uma descrição legível; para
     * as demais, devolve a própria expressão.
     */
    private function humanize(string $expression): string
    {
        $known = [
            '* * * * *' => __('crud_system.schedule_every_minute'),
            '0 * * * *' => __('crud_system.schedule_hourly'),
            '0 0 * * *' => __('crud_system.schedule_daily'),
            '0 0 * * 0' => __('crud_system.schedule_weekly'),
            '0 0 1 * *' => __('crud_system.schedule_monthly'),
        ];

        if (isset($known[$expression])) {
            return (string) $known[$expression];
        }

        // Ex.: "0 3 * * *" -> "diariamente às 03:00".
        if (preg_match('/^(\d{1,2}) (\d{1,2}) \* \* \*$/', $expression, $m) === 1) {
            return (string) __('crud_system.schedule_daily_at', [
                'time' => sprintf('%02d:%02d', (int) $m[2], (int) $m[1]),
            ]);
        }

        return $expression;
    }

    private function nextRun(Event $event): ?string
    {
        try {
            return (new CronExpression($event->expression))
                ->getNextRunDate('now', 0, false, $this->timezone($event))
                ->format('Y-m-d H:i:s');
        } catch (Throwable) {
            return null;
        }
    }
}
