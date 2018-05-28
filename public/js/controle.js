    var controle = {
        ajustarPWM: function (element, ip, pin) {
            var val = parseInt($(element).val());
            if (val == 0) {
                $(element).find('span').addClass('red');
            } else {
                $(element).find('span').removeClass('red');
            }
            console.log(ip + ':' + pin + '= ' + val);
        },
        onOff: function (pin_id) {
            $.get(window.location.href+'/onOff/'+pin_id, function (data) {
                $('#groups').remove();
                $('#divGroups').append(data);
            });
        }
    }