<?php

namespace Modules\Ishoppingcart\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Ishoppingcart\Entities\Payment;

//use Modules\Ishoppingcart\Http\Requests\IshoppingcartRequest;
use Modules\Ishoppingcart\Http\Requests\IshoppingcartPaymentRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;


class PaymentController extends BcrudController
{
    /**
     * @var PaymentRepository
     */
    private $payment;
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
        $this->crud->setModel('Modules\Ishoppingcart\Entities\Payment');
        $this->crud->setRoute('backend/ishoppingcart/payment');
        $this->crud->setEntityNameStrings(trans('ishoppingcart::payment.single'), trans('ishoppingcart::payment.plural'));
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
            'name' => 'name',
            'label' => trans('ishoppingcart::common.title'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => trans('ishoppingcart::common.description'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => trans('ishoppingcart::common.created_at'),
        ]);



        // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'name',
            'label' => trans('ishoppingcart::common.title'),
            'viewposition' => 'left',
        ]);

        $this->crud->addField([ 
            'name' => 'description',      
            'label' => trans('ishoppingcart::common.description'),
            'type' => 'wysiwyg',
            'viewposition' => 'left',
        ]);

        $this->crud->addField([
            'name' => 'config',
            'label' => trans('ishoppingcart::payment.config.title'),
            'type' => 'table',
            'entity_singular' => trans('ishoppingcart::payment.config.title'),
            'columns' => [
                'label' => trans('ishoppingcart::payment.config.type'),
                'desc' => trans('ishoppingcart::payment.config.description'),
            ],
            'viewposition' => 'left',
            'max' => 12, // maximum rows allowed in the table
            'min' => 0 // minimum rows allowed in the table
        ]);

        $this->crud->addField([
            'name'        => 'status',
            'label'       => trans('ishoppingcart::payment.status.title'),
            'type'        => 'radio',
            'options'     => [
                0 => trans('ishoppingcart::payment.status.inactive'),
                1 => trans('ishoppingcart::payment.status.active')
            ],
            'viewposition' => 'right',
        ]);

      

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

            if($this->auth->hasAccess("ishoppingcart.payments.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

        }
        $this->crud->access = $allowpermissions;
    }

    
    public function store(IshoppingcartPaymentRequest $request) {
       
        return parent::storeCrud($request);

    }
   

    public function update(IshoppingcartPaymentRequest $request) {
       
        return parent::updateCrud($request);
    }


   

}
