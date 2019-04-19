    var controle = {
        ajustarPWM: function (element) {
            $.get(window.location.href+'/ajustarPWM/'+$(element).data('id')+'/'+parseInt($(element).val()), function (data) {
                $('#groups').remove();
                $('#divGroups').append(data);
            });
        },
        onOff: function (pin_id) {
            $.get(window.location.href+'/onOff/'+pin_id, function (data) {
                $('#groups').remove();
                $('#divGroups').append(data);
            });
        }
    }