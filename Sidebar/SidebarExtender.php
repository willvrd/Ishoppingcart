<?php

namespace Modules\Ishoppingcart\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {

            $group->item(trans('ishoppingcart::common.ishoppingcart'), function (Item $item) {
                $item->icon('fa fa-shopping-bag');

                $item->item(trans('ishoppingcart::order.list'), function (Item $item) {
                    $item->icon('fa fa-pencil-square-o');
                    $item->weight(5);
                    $item->append('crud.ishoppingcart.order.create');
                    $item->route('crud.ishoppingcart.order.index');
                    $item->authorize(
                        $this->auth->hasAccess('ishoppingcart.orders.index')
                    );
                });

                $item->item(trans('ishoppingcart::coupon.list'), function (Item $item) {
                    $item->icon('fa fa-newspaper-o');
                    $item->weight(5);
                    $item->append('crud.ishoppingcart.coupon.create');
                    $item->route('crud.ishoppingcart.coupon.index');
                    $item->authorize(
                        $this->auth->hasAccess('ishoppingcart.coupons.index')
                    );
                });

                $item->item(trans('ishoppingcart::payment.list'), function (Item $item) {
                    $item->icon('fa fa-credit-card');
                    $item->weight(5);
                    $item->append('crud.ishoppingcart.payment.create');
                    $item->route('crud.ishoppingcart.payment.index');
                    $item->authorize(
                        $this->auth->hasAccess('ishoppingcart.payments.index')
                    );
                });

                $item->item(trans('ishoppingcart::orderCoupon.list'), function (Item $item) {
                    $item->icon('fa fa-list');
                    $item->weight(5);
                    $item->route('crud.ishoppingcart.orderCoupon.index');
                    $item->authorize(
                        $this->auth->hasAccess('ishoppingcart.orderCoupons.index')
                    );
                });


                $item->authorize(
                    $this->auth->hasAccess('ishoppingcart.orders.index')
                );


            });


        });

        return $menu;
    }
}
