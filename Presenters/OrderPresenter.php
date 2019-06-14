<?php

namespace Modules\Ishoppingcart\Presenters;

use Laracasts\Presenter\Presenter;
use Modules\Ishoppingcart\Entities\Status;

class orderPresenter extends Presenter
{
    /**
     * @var \Modules\Ishoppingcart\Entities\Status
     */
    protected $status;
    /**
     * @var \Modules\Ishoppingcart\Repositories\orderRepository
     */
    private $order;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->order = app('Modules\Ishoppingcart\Repositories\orderRepository');
        $this->status = app('Modules\Ishoppingcart\Entities\Status');
    }

    /**
     * Get the previous order of the current order
     * @return object
     */
    public function previous()
    {
        return $this->order->getPreviousOf($this->entity);
    }

    /**
     * Get the next order of the current order
     * @return object
     */
    public function next()
    {
        return $this->order->getNextOf($this->entity);
    }

    /**
     * Get the order status
     * @return string
     */
    public function status()
    {
        return $this->status->get($this->entity->status);
    }

    /**
     * Getting the label class for the appropriate status
     * @return string
     */
    public function statusLabelClass()
    {
        switch ($this->entity->status) {
            case Status::DRAFT:
                return 'bg-red';
                break;
            case Status::PENDING:
                return 'bg-orange';
                break;
            case Status::PUBLISHED:
                return 'bg-green';
                break;
            case Status::UNPUBLISHED:
                return 'bg-purple';
                break;
            default:
                return 'bg-red';
                break;
        }
    }
}
