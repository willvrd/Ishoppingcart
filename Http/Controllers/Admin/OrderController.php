<?php

namespace Modules\Ishoppingcart\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Ishoppingcart\Entities\Order;
use Modules\Ishoppingcart\Entities\Status;
use Modules\Ishoppingcart\Entities\Transaction_status;

//use Modules\Ishoppingcart\Http\Requests\IshoppingcartRequest;
use Modules\Ishoppingcart\Http\Requests\IshoppingcartOrderRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;


class OrderController extends BcrudController
{
    /**
     * @var OrderRepository
     */
    private $order;
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
        $this->crud->setModel('Modules\Ishoppingcart\Entities\Order');
        $this->crud->setRoute('backend/ishoppingcart/order');
        $this->crud->setEntityNameStrings(trans('ishoppingcart::order.single'), trans('ishoppingcart::order.plural'));
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
            'name' => 'type',
            'label' => trans('ishoppingcart::order.type'),
        ]);
       

        $this->crud->addColumn([
            'name' => 'amount',
            'label' => trans('ishoppingcart::order.amount'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => trans('ishoppingcart::common.created_at'),
        ]);



        // ------ CRUD FIELDS
        
        $this->crud->addField([
            'name' => 'type',
            'label' => trans('ishoppingcart::order.type'),
            'viewposition' => 'left',
        ]);
       
        $this->crud->addField([ 
            'name' => 'amount',      
            'label' => trans('ishoppingcart::order.amount'),
            'type' => 'number',
            'attributes' => ["step" => "any"], // allow decimals
            'viewposition' => 'left',
        ]);

        $this->crud->addField([  // Select
            'label' => trans('ishoppingcart::common.author'),
            'type' => 'select',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'email', // foreign key attribute that is shown to user
            'model' => "Modules\\User\\Entities\\{$driver}\\User", // foreign key model,
            'viewposition' => 'right',
        ]);

        $this->crud->addField([
            'name'        => 'status',
            'label'       => trans('ishoppingcart::common.status_text'),
            'type'        => 'radio',
            'options'     => [
                0 => trans('ishoppingcart::common.transaction_status.declined'),
                1 => trans('ishoppingcart::common.transaction_status.approved'),
                2 => trans('ishoppingcart::common.transaction_status.pending'),
                3 => trans('ishoppingcart::common.transaction_status.expired'),
                4 => trans('ishoppingcart::common.transaction_status.error')
            ],
            'viewposition' => 'right',
        ]);

        /*
        $this->crud->addField([
            'name'        => 'status',
            'label'       => trans('ishoppingcart::common.status_text'),
            'type'        => 'radio',
            'options'     => [
                0 => trans('ishoppingcart::common.status.draft'),
                1 => trans('ishoppingcart::common.status.pending'),
                2 => trans('ishoppingcart::common.status.published'),
                3 => trans('ishoppingcart::common.status.unpublished')
            ],
            'viewposition' => 'right',
        ]);
        */
        
    }

    public function index()
    {
        parent::index();

        return view('ishoppingcart::admin.list', $this->data);
    }
    
    public function edit($id) {
        parent::edit($id);

       return view('ishoppingcart::admin.edit', $this->data);

    }

    public function create() {

        parent::create();

        return view('ishoppingcart::admin.create', $this->data);

    }
    public function show($id=null) {

        parent::show($id=null);

        return view('ishoppingcart::admin.show', $this->data);

    }

    public function setup()
    {
        parent::setup();

        $permissions = ['index', 'create', 'edit', 'destroy'];
        $allowpermissions = ['show'];
        foreach($permissions as $permission) {

            if($this->auth->hasAccess("ishoppingcart.orders.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

        }
        $this->crud->access = $allowpermissions;
    }

    
    public function store(IshoppingcartOrderRequest $request) {
       
        return parent::storeCrud($request);

    }
   
    public function update(IshoppingcartOrderRequest $request) {
       
        return parent::updateCrud($request);
    }

   

}
