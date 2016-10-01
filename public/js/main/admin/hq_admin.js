var HQ = function () {
    return {
        init: function ()
        {
            $(document).ready(function () {
                $('body').on('hidden.bs.modal', '.modal', function () {
                    $(this).removeData('bs.modal');
                });
            });
        },
        //main function to initiate the module
        showLoading: function (id) {
            if (typeof (id) != "undefined")
            {
                $("#" + id).block({
                    baseZ: 2000,
                    message: '<i class="icon-spinner4 spinner"></i>',
                    timeout: 0, //unblock after 2 seconds
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            }
            else
            {
                $.blockUI({
                    baseZ: 2000,
                    message: '<i class="icon-spinner4 spinner"></i>',
                    timeout: 0, //unblock after 2 seconds
                    overlayCSS: {
                        backgroundColor: '#1b2024',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        color: '#fff',
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            }
        },
        offLoading: function (id)
        {
            if (typeof (id) != "undefined")
            {
                $("#" + id).unblock();
            }
            else
            {
                $.unblockUI();
            }

        },
        successMessage: function (text)
        {
            new PNotify({
                title: HQLanguage.ajax_success_title,
                text: text,
                addclass: 'alert-styled-left',
                type: 'success'
            });
        },
        failMessage: function (text)
        {
            new PNotify({
                title: HQLanguage.ajax_fail_title,
                text: text,
                addclass: 'alert-styled-left',
                type: 'error'
            });
        }
    };
}();
function iconFormat(state) {
    var originalOption = state.element;
    return "<i class='icon-" + $(originalOption).data('icon') + "'></i>" + state.text;
}
HQ.init();