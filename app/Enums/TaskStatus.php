<?php

namespace App\Enums;

enum TaskStatus: string
{
    case READY_TO_DEV = 'ready_to_dev';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case QA = 'qa';
    case READY_TO_PROD = 'ready_to_prod';
    case DONE = 'done';

    public function statusTitle(): string
    {
        return match ($this) {
            self::READY_TO_DEV  => 'Готова к разработке',
            self::IN_PROGRESS   => 'В работе',
            self::REVIEW        => 'На ревью',
            self::QA            => 'Передана в тестирование',
            self::READY_TO_PROD => 'Готова к продакшну',
            self::DONE          => 'Выполнена',
        };
    }
}