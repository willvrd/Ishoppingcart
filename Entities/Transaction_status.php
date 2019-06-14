<?php

namespace Modules\Ishoppingcart\Entities;

/**
 * Class Status
 * @package Modules\ishoppingCar\Entities
 */
class Transaction_status
{
    const DECLINED = 0;
    const APPROVED = 1;
    const PENDING = 2;
    const EXPIRED = 3;
    const ERROR = 4;

    /**
     * @var array
     */
    private $statuses = [];

    public function __construct()
    {
        $this->statuses = [
            self::DECLINED => trans('ishoppingcart::common.transaction_status.declined'),
            self::APPROVED => trans('ishoppingcart::common.transaction_status.approved'),
            self::PENDING => trans('ishoppingcart::common.transaction_status.pending'),
            self::EXPIRED => trans('ishoppingcart::common.transaction_status.expired'),
            self::ERROR => trans('ishoppingcart::common.transaction_status.error'),
        ];
    }

    /**
     * Get the available statuses
     * @return array
     */
    public function lists()
    {
        return $this->statuses;
    }

    /**
     * Get the post status
     * @param int $statusId
     * @return string
     */
    public function get($statusId)
    {
        if (isset($this->statuses[$statusId])) {
            return $this->statuses[$statusId];
        }

        return $this->statuses[self::PENDING];
    }
}
