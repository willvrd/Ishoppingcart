<!-- select2 -->
<div @include('bcrud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <?php $entity_model = $crud->getModel(); ?>

    <div class="row checkbox">
        @foreach ($field['model']::all() as $connected_entity_entry)
                <div class="checkbox col-xs-12">
                  <label>
                    <input type="checkbox" class="flat-blue jsInherit"
                      name="{{ $field['name'] }}[]"
                      value="{{ $connected_entity_entry->id }}"

                      @if( ( old( $field["name"] ) && in_array($connected_entity_entry->id, old( $field["name"])) ) || (isset($field['value']) && in_array($connected_entity_entry->id, $field['value']->pluck('id', 'id')->toArray())))
                             checked = "checked"
                      @endif > {!! $connected_entity_entry->{$field['attribute']} !!}
                  </label>
            </div>
        @endforeach
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
@push('crud_fields_styles')
{{--
<link href="{{ asset('modules/ishoppingcart/vendor/checkbox-inline/build.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/ishoppingcart/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet" type="text/css" />--}}
@endpush

@push('crud_fields_scripts')
<script>
    jQuery(document).ready(function($) {
        $('input[type="checkbox"].flat-blue, input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
    });
</script>
@endpush