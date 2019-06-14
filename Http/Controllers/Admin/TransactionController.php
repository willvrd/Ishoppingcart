<?php

namespace Modules\Ishoppingcart\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Ishoppingcart\Entities\Transaction;
use Modules\Ishoppingcart\Entities\Transaction_status;

use Modules\Ishoppingcart\Http\Requests\IshoppingcartRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;


class TransactionController extends BcrudController
{
    /**
     * @var PostRepository
     */
    private $transaction;
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
        $this->crud->setModel('Modules\Ishoppingcart\Entities\Transaction');
        $this->crud->setRoute('backend/ishoppingcart/transaction');
        $this->crud->setEntityNameStrings(trans('ishoppingcart::transaction.single'), trans('ishoppingcart::transaction.plural'));
        $this->access = [];
       
    }

    public function index()
    {

        parent::index();
        return view('ishoppingcart::admin.list', $this->data);
    }

    public function edit($id) {
    
        parent::edit($id);
        return view('ishoppingcart::admin.edit_post', $this->data);

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

            if($this->auth->hasAccess("ishoppingcart.transactions.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

        }
        $this->crud->access = $allowpermissions;
    }


    
    public function store(IshoppingcartRequest $request) {
       
        return parent::storeCrud($request);

    }
   

    public function update(IshoppingcartRequest $request) {
       
        return parent::updateCrud($request);

    }


}
