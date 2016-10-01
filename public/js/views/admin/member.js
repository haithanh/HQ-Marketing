$(document).ready(function () {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
                orderable: false,
                width: '100px',
                targets: [5]
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
    var data_id = "member-panel";
    var table_data = $("#" + data_id + " .table").DataTable({
        'ajax': {
            'type': "POST",
            'beforeSend': function () {
                HQ.showLoading(data_id);
            },
            'complete': function () {
                HQ.offLoading(data_id);
            },
            'url': rootUrl + "member/searchmember",
            'data': function (d) {
                d.member_data = $("#member_data").val();
            }
        }
    });
    $(".filter-table").click(function () {
        table_data.ajax.reload();
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: "-1"
    });

});