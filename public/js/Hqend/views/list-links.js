$(document).ready(function () {
    //Form Items
    $('.select-search').select2();
    $('.pickadate-selectors').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd',
        format: 'yyyy-mm-dd'
    });
    //Form Items / End
    $.extend($.fn.dataTable.defaults, {
        autoWidth: true,
        columnDefs: [{
                orderable: true,
                width: '100px',
                targets: [2]
            }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
    var data_id = "link-table";
    var table_data = $("#" + data_id).DataTable({
        'ajax': {
            'type': "POST",
            'beforeSend': function () {
                HQ.showLoading(data_id);
            },
            'complete': function () {
                HQ.offLoading(data_id);
            },
            'url': rootUrl + "link/filter-link",
            'data': function (d) {
                var form = {};
                $.each($("#filter-form").serializeArray(), function (i, field) {
                    d[field.name] = field.value || "";
                });
            }
        }
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: "-1"
    });
    $("#filter-button").click(function () {
        table_data.ajax.reload();
    });

});
function editLink(id)
{
    HQ.showLoading();
    $.ajax({
        type: "GET",
        url: rootUrl + "link/edit-link?id=" + id
    }).done(function (msg) {
        HQ.offLoading();
        $("#modal_edit_member .modal-content").html(msg);
        $('#modal_edit_member .select-search').select2();
        $(".switch").bootstrapSwitch();
        $("#modal_edit_member").modal();
    }).error(function (err) {
        HQ.offLoading();
        HQ.failMessage(HQLanguage.ajax_fail_message);
    });
}