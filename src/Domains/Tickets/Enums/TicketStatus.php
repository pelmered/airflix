<?php

namespace Domain\Tickets\Enums;

use Support\BaseClasses\BaseEnum;

class TicketStatus extends BaseEnum
{
    public const DEFAULT = self::CONFIRMED;

    public const PENDING_PAYMENT = 'pending_payment';
    public const CONFIRMED = 'confirmed';

    public const CANCELLED = 'cancelled';

    /**
     * @return array<(array|string|null)>
     *
     * @psalm-return array{pending_info: (array|string|null), pending_approval: (array|string|null), coming_soon: (array|string|null), active: (array|string|null), testing: (array|string|null), not_available: (array|string|null)}
     */
    public static function options()
    {
        return [
            self::PENDING_PAYMENT => __('Pending payment'),
            self::CONFIRMED => __('Confirmed'),
            self::CANCELLED => __('Cancelled'),
        ];
    }
}
