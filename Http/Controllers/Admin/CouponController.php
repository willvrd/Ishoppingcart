<?php

namespace Modules\Ishoppingcart\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Ishoppingcart\Entities\Coupon;

//use Modules\Ishoppingcartt\Http\Requests\IshoppingcartRequest;
use Modules\Ishoppingcart\Http\Requests\IshoppingcartCouponRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;

use Modules\Setting\Contracts\Setting;

class CouponController extends BcrudController
{
    /**
     * @var PostRepository
     */
    private $coupon;
    private $auth;
    private $setting;
   
    public function __construct(Authentication $auth, Setting $setting)
    {
        parent::__construct();

        $this->auth = $auth;
        $driver = config('asgard.user.config.driver');
        $this->setting = $setting;

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Modules\Ishoppingcart\Entities\Coupon');
        $this->crud->setRoute('backend/ishoppingcart/coupon');
        $this->crud->setEntityNameStrings(trans('ishoppingcart::coupon.single'), trans('ishoppingcart::coupon.plural'));
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
            'name' => 'code',
            'label' => trans('ishoppingcart::coupon.code'),
        ]);

        $this->crud->addColumn([
            'name' => 'cant',
            'label' => trans('ishoppingcart::coupon.cant'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => trans('ishoppingcart::common.created_at'),
        ]);



        // ------ CRUD FIELDS

         $this->crud->addField([
            'name' => 'name',
            'label' => trans('ishoppingcart::common.title'),
            'type' => 'text',
            'viewposition' => 'left',
        ]);

        $this->crud->addField([
            'name' => 'code',
            'label' => trans('ishoppingcart::coupon.code'),
            'type' => 'text',
            'default' => $this->addCouponCode(),
            'attributes' => ['readonly' => 'readonly'],
            'viewposition' => 'left',
        ]);

        $this->crud->addField([ 
            'name' => 'type',
            'label' => trans('ishoppingcart::coupon.type'),
            'type' => 'select_from_array',
            'options' => [
                'p' => trans('ishoppingcart::coupon.percentage'),
                'f' => trans('ishoppingcart::coupon.fixed')
            ],
            'allows_null' => false,
            'viewposition' => 'left',
        ]);
       
        $this->crud->addField([
            'name' => 'cant',
            'label' => trans('ishoppingcart::coupon.cant'),
            'type' => 'number',
            'viewposition' => 'left',
        ]);

        $this->crud->addField([
            'name' => 'value',
            'label' => trans('ishoppingcart::coupon.value'),
            'type' => 'number',
            'viewposition' => 'left',
        ]);

        $this->crud->addField([
            'name' => 'from',
            'label' => trans('ishoppingcart::coupon.from'),
            'type' => 'date',
            'viewposition' => 'right',
        ]);

         $this->crud->addField([
            'name' => 'to',
            'label' => trans('ishoppingcart::coupon.to'),
            'type' => 'date',
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

            if($this->auth->hasAccess("ishoppingcart.coupons.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

        }
        $this->crud->access = $allowpermissions;
    }

    
    public function store(IshoppingcartCouponRequest $request) {

       return parent::storeCrud($request);

    }
   

    public function update(IshoppingcartCouponRequest $request) {
        
        return parent::updateCrud($request);

    }


    public function addCouponCode(){

        $code = "";

        $couponFormat = $this->setting->get('ishoppingcart::coupon-format');
        $codePrefix = $this->setting->get('ishoppingcart::code-prefix');
        $codeSufix = $this->setting->get('ishoppingcart::code-sufix');
        
        $uppercase = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];

        $lowercase = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];

        $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        $characters = [];

        if(empty($couponFormat))
            $couponFormat = "alphabetic";

        if($couponFormat=="alphabetic"){
            $characters = array_merge($characters, $lowercase, $uppercase);  
        }

        if($couponFormat=="numeric"){
            $characters = array_merge($characters, $numbers);  
        }

        if($couponFormat=="alphanumeric"){
            $characters = array_merge($characters, $numbers, $lowercase, $uppercase);  
        }

        // Asumiendo que se pudiera repetir el codigo que se genera
        //=============================================
        $result = 1;

        while($result==1){
            $code = $this->generaterCouponCode($characters);
            $result = Coupon::where("code",$code)->count();
        }
        //=============================================

       
        if(!empty($codePrefix))
            $codePrefix = $codePrefix ."-";

        if(!empty($codeSufix))
            $codeSufix = "-".$codeSufix;

        return $codePrefix.$code.$codeSufix;

    }

    public function generaterCouponCode($characters){

        $cont = 0;
        $contdash = 0;
        $code = "";

        $codeLENGHT = $this->setting->get('ishoppingcart::code-lenght');
        $dashEvery = $this->setting->get('ishoppingcart::dash-every');

        while($cont < $codeLENGHT){

            $code .= $characters[mt_rand(0, count($characters) - 1)];

            $contdash++;
            $cont++;

            if($contdash==$dashEvery && $cont<$codeLENGHT){
                $code .= "-";
                $contdash = 0;
            }

        }

        return $code;

    }



   
}