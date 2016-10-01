var HQMain = function () {
    return {
        ajax: function (url, data, success_callback, fail_callback)
        {
            HQ.showLoading();
            $.ajax({
                type: "POST",
                url: url,
                data: data
            }).done(function (msg) {
                HQ.offLoading();
                var fail = false;
                try
                {
                    var result = $.parseJSON(msg);
                    if (result.status === 1)
                    {
                        HQ.successMessage(result.message);
                        if (typeof (success_callback) != "undefined")
                        {
                            success_callback(result);
                        }
                    }
                    else
                    {
                        HQ.failMessage(result.message);
                        if (typeof (fail_callback) != "undefined")
                        {
                            fail_callback(result);
                        }
                    }
                } catch (e)
                {
                    HQ.failMessage(HQLanguage.ajax_fail_message);
                }
            }).error(function (err) {
                HQ.offLoading();
                HQ.failMessage(HQLanguage.ajax_fail_message);
            });
        }
    };
}();