@extends('layouts.master')

@section('after_styles')
	<!-- DATA TABLES -->
    <link href="{{ asset('vendor/adminlte/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content-header')
	<section class="content-header">
	  <h1>
	    <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
	    <small>{{ trans('bcrud::crud.all') }} <span class="text-lowercase">{{ $crud->entity_name_plural }}</span> {{ trans('bcrud::crud.in_the_database') }}.</small>
	  </h1>
	  <ol class="breadcrumb">
        <li><a href="{{URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('bcrud::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-header {{ $crud->hasAccess('create')?'with-border':'' }}">

      @include('bcrud::inc.button_stack', ['stack' => 'top'])

      <div id="datatable_button_stack" class="pull-right text-right"></div>
    </div>
    <div class="box-body">

		<table id="crudTable" class="table table-bordered table-striped display">
        <thead>
          <tr>
            @if ($crud->details_row)
              <th></th> <!-- expand/minimize button column -->
            @endif

            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
              <th>{{ $column['label'] }}</th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
              <th>{{ trans('bcrud::crud.actions') }}</th>
            @endif
          </tr>
        </thead>
        <tbody>

          @if (!$crud->ajaxTable())
            @foreach ($entries as $k => $entry)
            <tr data-entry-id="{{ $entry->getKey() }}">

              @if ($crud->details_row)
                @include('bcrud::columns.details_row_button')
              @endif

              {{-- load the view from the application if it exists, otherwise load the one in the package --}}
              @foreach ($crud->columns as $column)
                @if (!isset($column['type']))
                  @include('bcrud::columns.text')
                @else
                  @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                    @include('vendor.backpack.crud.columns.'.$column['type'])
                  @else
                    @if(view()->exists('bcrud::columns.'.$column['type']))
                      @include('bcrud::columns.'.$column['type'])
                    @else
                      @include('bcrud::columns.text')
                    @endif
                  @endif
                @endif

              @endforeach

              @if ($crud->buttons->where('stack', 'line')->count())
                <td>
                  @include('bcrud::inc.button_stack', ['stack' => 'line'])
                </td>
              @endif

            </tr>
            @endforeach
          @endif

        </tbody>
        <tfoot>
          <tr>
            @if ($crud->details_row)
              <th></th> <!-- expand/minimize button column -->
            @endif

            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
              <th>{{ $column['label'] }}</th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
              <th>{{ trans('bcrud::crud.actions') }}</th>
            @endif
          </tr>
        </tfoot>
      </table>

    </div><!-- /.box-body -->

    @include('bcrud::inc.button_stack', ['stack' => 'bottom'])

  </div><!-- /.box -->
@endsection

@section('scripts')



	<!-- DATA TABLES SCRIPT -->
    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

    @if ($crud->exportButtons())
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js" type="text/javascript"></script>
    @endif

    <script src="{{ asset('vendor/adminlte/plugins/datatables/dataTables.bootstrap.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
	  jQuery(document).ready(function($) {

      $.ajaxPrefilter(function(options, originalOptions, xhr) {
          var token = $('meta[name="token"]').attr('value');

          if (token) {
              return xhr.setRequestHeader('X-CSRF-TOKEN', token);
          }
      });


      @if ($crud->exportButtons())
      var dtButtons = function(buttons){
          var extended = [];
          for(var i = 0; i < buttons.length; i++){
          var item = {
              extend: buttons[i],
              exportOptions: {
              columns: [':visible']
              }
          };
          switch(buttons[i]){
              case 'pdfHtml5':
              item.orientation = 'landscape';
              break;
          }
          extended.push(item);
          }
          return extended;
      };;;;;
      @endif

	  	var table = $("#crudTable").DataTable({
        "pageLength": {{ $crud->getDefaultPageLength() }},
        "language": {
              "emptyTable":     "{{ trans('bcrud::crud.emptyTable') }}",
              "info":           "{{ trans('bcrud::crud.info') }}",
              "infoEmpty":      "{{ trans('bcrud::crud.infoEmpty') }}",
              "infoFiltered":   "{{ trans('bcrud::crud.infoFiltered') }}",
              "infoPostFix":    "{{ trans('bcrud::crud.infoPostFix') }}",
              "thousands":      "{{ trans('bcrud::crud.thousands') }}",
              "lengthMenu":     "{{ trans('bcrud::crud.lengthMenu') }}",
              "loadingRecords": "{{ trans('bcrud::crud.loadingRecords') }}",
              "processing":     "{{ trans('bcrud::crud.processing') }}",
              "search":         "{{ trans('bcrud::crud.search') }}",
              "zeroRecords":    "{{ trans('bcrud::crud.zeroRecords') }}",
              "paginate": {
                  "first":      "{{ trans('bcrud::crud.paginate.first') }}",
                  "last":       "{{ trans('bcrud::crud.paginate.last') }}",
                  "next":       "{{ trans('bcrud::crud.paginate.next') }}",
                  "previous":   "{{ trans('bcrud::crud.paginate.previous') }}"
              },
              "aria": {
                  "sortAscending":  "{{ trans('bcrud::crud.aria.sortAscending') }}",
                  "sortDescending": "{{ trans('bcrud::crud.aria.sortDescending') }}"
              }
          },

          @if ($crud->ajaxTable())
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": "{{ url($crud->route.'/search') }}",
              "type": "POST"
          },
          @endif

          @if ($crud->exportButtons())
          // show the export datatable buttons
          dom: '<"p-l-0 col-md-6"l>B<"p-r-0 col-md-6"f>rt<"col-md-6 p-l-0"i><"col-md-6 p-r-0"p>',
          buttons: dtButtons([
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'print',
            'colvis'
          ]),
          @endif
      });

      @if ($crud->exportButtons())
      // move the datatable buttons in the top-right corner and make them smaller
      table.buttons().each(function(button) {
        if (button.node.className.indexOf('buttons-columnVisibility') == -1)
        {
          button.node.className = button.node.className + " btn-sm";
        }
      });;;;;
      $(".dt-buttons").appendTo($('#datatable_button_stack' ));
      @endif



      // make the delete button work in the first result page
      register_delete_button_action();

      // make the delete button work on subsequent result pages
      $('#crudTable').on( 'draw.dt',   function () {
         register_delete_button_action();

         @if ($crud->details_row)
          register_details_row_button_action();
         @endif
      } ).dataTable();

      function register_delete_button_action() {
        $("[data-button-type=delete]").unbind('click');
        // CRUD Delete
        // ask for confirmation before deleting an item
        $("[data-button-type=delete]").click(function(e) {
          e.preventDefault();
          var delete_button = $(this);
          var delete_url = $(this).attr('href');

          if (confirm("{{ trans('bcrud::crud.delete_confirm') }}") == true) {
              $.ajax({
                  url: delete_url,
                  type: 'DELETE',
                  success: function(result) {
                      // Show an alert with the result
                      new PNotify({
                          title: "{{ trans('bcrud::crud.delete_confirmation_title') }}",
                          text: "{{ trans('bcrud::crud.delete_confirmation_message') }}",
                          type: "success"
                      });
                      // delete the row from the table
                      delete_button.parentsUntil('tr').parent().remove();
                  },
                  error: function(result) {
                      // Show an alert with the result
                      new PNotify({
                          title: "{{ trans('bcrud::crud.delete_confirmation_not_title') }}",
                          text: "{{ trans('bcrud::crud.delete_confirmation_not_message') }}",
                          type: "warning"
                      });
                  }
              });
          } else {
              new PNotify({
                  title: "{{ trans('bcrud::crud.delete_confirmation_not_deleted_title') }}",
                  text: "{{ trans('bcrud::crud.delete_confirmation_not_deleted_message') }}",
                  type: "info"
              });
          }
        });
      }


      @if ($crud->details_row)
      function register_details_row_button_action() {
        // Add event listener for opening and closing details
        $('#crudTable tbody').on('click', 'td .details-row-button', function () {
            var tr = $(this).closest('tr');
            var btn = $(this);
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                $(this).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                $('div.table_row_slider', row.child()).slideUp( function () {
                    row.child.hide();
                    tr.removeClass('shown');
                } );
            }
            else {
                // Open this row
                $(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                // Get the details with ajax
                $.ajax({
                  url: '{{ Request::url() }}/'+btn.data('entry-id')+'/details',
                  type: 'GET',
                  // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                  // data: {param1: 'value1'},
                })
                .done(function(data) {
                  // console.log("-- success getting table extra details row with AJAX");
                  row.child("<div class='table_row_slider'>" + data + "</div>", 'no-padding').show();
                  tr.addClass('shown');
                  $('div.table_row_slider', row.child()).slideDown();
                  register_delete_button_action();
                })
                .fail(function(data) {
                  // console.log("-- error getting table extra details row with AJAX");
                  row.child("<div class='table_row_slider'>{{ trans('bcrud::crud.details_row_loading_error') }}</div>").show();
                  tr.addClass('shown');
                  $('div.table_row_slider', row.child()).slideDown();
                })
                .always(function(data) {
                  // console.log("-- complete getting table extra details row with AJAX");
                });
            }
        } );
      }

      register_details_row_button_action();
      @endif


	  });
	</script>
@endsection
