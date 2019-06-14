@extends('layouts.master')

@section('header')
    <section class="content-header">
        <h1>
            {{ trans('bcrud::crud.edit') }} <span class="text-lowercase">{{ $crud->entity_name }}</span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}"><i
                            class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('bcrud::crud.edit') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Default box -->
            @if ($crud->hasAccess('list'))
                <a href="{{ url($crud->route) }}"><i
                            class="fa fa-angle-double-left"></i> {{ trans('bcrud::crud.back_to_all') }} <span
                            class="text-lowercase">{{ $crud->entity_name_plural }}</span></a><br><br>
            @endif

            {!! Form::open(array('url' => $crud->route.'/'.$entry->getKey(), 'method' => 'put', 'files'=>$crud->hasUploadFields('create'))) !!}
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('bcrud::crud.edit') }}</h3>
                </div>
                <div class="box-body row">

                    <!-- load the view from the application if it exists, otherwise load the one in the package -->
                    @if(view()->exists('vendor.backpack.crud.form_content'))
                        @include('vendor.backpack.crud.form_content')
                    @else
                        @include('ishoppingcart::admin.form_content', ['fields' => $crud->getFields('update', $entry->getKey())])
                    @endif

                </div><!-- /.box-body -->
                <div class="box-footer">

                    <button type="submit" class="btn btn-success ladda-button" data-style="zoom-in"><span
                                class="ladda-label"><i class="fa fa-save"></i> {{ trans('bcrud::crud.save') }}</span>
                    </button>
                    <a href="{{ url($crud->route) }}" class="btn btn-default ladda-button" data-style="zoom-in"><span
                                class="ladda-label">{{ trans('bcrud::crud.cancel') }}</span></a>
                </div><!-- /.box-footer-->
            </div><!-- /.box -->
            {!! Form::close() !!}
        </div>


        @if(Route::has('ishoppingcart.gallery.upload'))

        <div class="col-md-12">
            {{-- dd(public_path()) --}}
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{trans('ishoppingcart::post.form.gallery')}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <br/>

                <div style="padding:0 15px 20px">
                    <form id="real-dropzone" class="dropzone" enctype="multipart/form-data"
                          action="{{URL::route('ishoppingcart.gallery.upload')}}" method="post">
                        <div class="row">
                            <h4 style="text-align: center;">{{trans('ishoppingcart::post.form.drag')}}<i
                                        class="glyphicon glyphicon-download-alt" aria-hidden="true"></i></h4>
                            <input type="hidden" name="idarticulo" value="">
                            {{csrf_field()}}
                            <input type="hidden" id="idedit" name="idedit" value="{{ $entry->id }}">
                            <div class="dz-message"><span class="h4">{{trans('ishoppingcart::post.form.click')}}</span></div>
                            <div class="fallback"><input name="file" type="file" multiple/></div>
                            <div class="dropzone-previews" id="dropzonePreview">

                                @foreach(Storage::disk('publicmedia')->files('assets/ishoppingcart/post/gallery/' . $entry->id) as $image)
                                    <div class="dz-preview dz-processing dz-image-preview dz-complete">
                                        <div class="dz-image">
                                            <img data-dz-thumbnail="" alt="Jellyfish.jpg" src="{{ asset($image) }}">
                                        </div>
                                        <div class="dz-details">

                                        </div>
                                        <div class="dz-progress">
                                            <span class="dz-upload" data-dz-uploadprogress=""
                                                  style="width: 100%;"></span>
                                        </div>
                                        <div class="dz-error-message">
                                            <span data-dz-errormessage=""></span>
                                        </div>
                                        <a class="dz-remove btn btn-danger btn-xs" href="javascript:undefined;"
                                           onclick="$.deleteFile(this)" dir-data="{{ $image }}">Eliminar</a>
                                    </div>
                                @endforeach

                            </div>
                            <!-- Dropzone Preview Template -->
                            <div id="preview-template" style="display: none;">

                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-image"><img data-dz-thumbnail=""></div>

                                    <div class="dz-details">
                                        <div class="dz-size"><span data-dz-size=""></span></div>
                                        <div class="dz-filename"><span data-dz-name=""></span></div>
                                    </div>

                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span>
                                    </div>
                                    <div class="dz-success-mark">
                                        <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                            <title>Check</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd"
                                               sketch:type="MSPage">
                                                <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                      id="Oval-2" stroke-opacity="0.198794158" stroke="#747474"
                                                      fill-opacity="0.816519475" fill="#FFFFFF"
                                                      sketch:type="MSShapeGroup"></path>
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="dz-error-mark">
                                        <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                            <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                            <title>error</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd"
                                               sketch:type="MSPage">
                                                <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474"
                                                   stroke-opacity="0.198794158" fill="#FFFFFF"
                                                   fill-opacity="0.816519475">
                                                    <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                                                          id="Oval-2" sketch:type="MSShapeGroup"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <!--<div class="dz-error-message"><span data-dz-errormessage></span></div>-->
                                </div>
                            </div>
                            <!-- End Dropzone Preview Template -->
                        </div>
                    </form>
                </div>
                <!-- Dropzone Preview Template -->
                <div id="preview-template" style="display: none;">
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image"><img data-dz-thumbnail=""></div>

                        <div class="dz-details">
                        </div>

                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                    </div>
                </div>
                <!-- End Dropzone Preview Template -->

            </div>
        </div>

        @endif


    </div>

    <link href="{{ asset('modules/ishoppingcart/vendor/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('modules/ishoppingcart/vendor/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('modules/ishoppingcart/vendor/dropzone/dropzone-config.js') }}"></script>

@endsection
