$(document).ready(function () {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: true,
        columnDefs: [{
                orderable: true,
                width: '100px',
                targets: [4]
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
            'url': rootUrl + "member-vip/search-member",
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
function deleteMemberVip(id)
{
    swal({
        title: HQLanguage.ajax_delete_title,
        text: "",
        type: "warning",
        confirmButtonColor: "#DD6B55",
        showCancelButton: true,
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true,
    },
            function (isConfirm) {
                if (isConfirm) {
                    HQMain.ajax(rootUrl + "member-vip/delete-member", {id: id}, function () {
                        swal(HQLanguage.ajax_success_title, "", "success");
                    });
                } else {
                    swal(HQLanguage.ajax_cancel_title, "", "error");
                }
            });
}