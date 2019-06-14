<?php

namespace Modules\Ishoppingcart\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Ishoppingcart\Entities\OrderCoupon;

use Modules\Ishoppingcart\Http\Requests\IshoppingcartRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;


class OrderCouponController extends BcrudController
{
    /**
     * @var PostRepository
     */
    private $orderCoupon;
    private $auth;
   
    public function __construct(Authentication $auth)
    {
        parent::__construct();

        $this->auth = $auth;
        $driver = config('asgard.user.config.driver');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Modules\Ishoppingcart\Entities\OrderCoupon');
        $this->crud->setRoute('backend/ishoppingcart/orderCoupon');
        $this->crud->setEntityNameStrings(trans('ishoppingcart::orderCoupon.single'), trans('ishoppingcart::orderCoupon.plural'));
        $this->access = [];
        $this->crud->enableAjaxTable();
        $this->crud->orderBy('created_at', 'DESC');
        $this->crud->limit(100);


        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'name' => 'id',
            'label' => 'ID',
        ]);

        $this->crud->addColumn([
            'name' => 'order_id',
            'label' => trans('ishoppingcart::order.single'),// Table column heading
        ]);

        $this->crud->addColumn([
            'name' => 'coupon_id',
            'label' => trans('ishoppingcart::coupon.single'),// Table column heading
        ]);
        
        /*
        $this->crud->addColumn([
            'name' => 'coupon_id',
            'label' => trans('ishoppingcart::coupon.single'),// Table column heading
            'type' => 'select',
            'attribute' => 'code',
            'entity' => 'coupon',
            'model' => "Modules\\Ishoppingcart\\Entities\\Coupon", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'order_id',
            'label' => trans('ishoppingcart::order.single'),// Table column heading
            'type' => 'select',
            'attribute' => 'type',
            'entity' => 'order',
            'model' => "Modules\\Ishoppingcart\\Entities\\Order", // foreign key model
        ]);
        */

        $this->crud->addColumn([
            'name' => 'value',
            'label' => trans('ishoppingcart::orderCoupon.value'),
        ]);

    }

    public function index()
    {
        parent::index();
        return view('ishoppingcart::admin.list', $this->data);
    }
    
    public function edit($id) {

        return \Redirect::to($this->crud->route);
        
    }

    public function create() {

       return \Redirect::to($this->crud->route); 

    }

    public function show($id=null) {

        return \Redirect::to($this->crud->route);   
    }

    public function setup()
    {
        parent::setup();

        $permissions = ['index', 'create', 'edit', 'destroy'];
        $allowpermissions = ['show'];
        foreach($permissions as $permission) {

            if($this->auth->hasAccess("ishoppingcart.orderCoupons.$permission")) {
                if($permission=='index') $permission = 'list';
                $allowpermissions[] = $permission;
            }

        }
        $this->crud->access = $allowpermissions;
    }

    public function store(IshoppingcartRequest $request) {
       
        return \Redirect::to($this->crud->route);  

    }

    public function update(IshoppingcartRequest $request) {
        
        return \Redirect::to($this->crud->route);

    }
 
}