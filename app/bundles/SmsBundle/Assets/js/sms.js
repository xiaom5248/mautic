/** SmsBundle **/
Mautic.smsOnLoad = function (container, response) {
    if (mQuery(container + ' #list-search').length) {
        Mautic.activateSearchAutocomplete('list-search', 'sms');
    }

    if (mQuery('#counter').length) {
        // mQuery(".toolwar").after("<div class='sms-toolbox'><button type='button' class='btn btn-default' data-toggle='modal' data-target='#shortenModal' data-whatever='@getbootstrap'>插入短链接</button></div>");
        //
        //     mQuery('.short_insert').click(function () {
        //     mQuery('#shortenModal').modal('toggle');
        //     var url = mQuery("#short-url").val();
        //     var request_url = "/s/sms/short?url="+url;
        //
        //     mQuery.ajax({
        //         type:"get",
        //         async:false,
        //         url:request_url,
        //         dataType:"json",
        //         success:function (json) {
        //             mQuery("#sms_message").val(mQuery("#sms_message").val()+json[0].url_short);
        //             Mautic.countChar('sms_message','counter');
        //             mQuery("#short-url").val("");
        //         }
        //     });
        // });
    }
};


Mautic.shortenUrl = function(url) {
    mQuery.ajax();
}

Mautic.selectSmsType = function(smsType) {
    if (smsType == 'list') {
        mQuery('#leadList').removeClass('hide');
        mQuery('#publishStatus').addClass('hide');
        mQuery('.page-header h3').text(mauticLang.newListSms);
    } else {
        mQuery('#publishStatus').removeClass('hide');
        mQuery('#leadList').addClass('hide');
        mQuery('.page-header h3').text(mauticLang.newTemplateSms);
    }

    mQuery('#sms_smsType').val(smsType);

    mQuery('body').removeClass('noscroll');

    mQuery('.sms-type-modal').remove();
    mQuery('.sms-type-modal-backdrop').remove();
};

Mautic.standardSmsUrl = function(options) {
    if (!options) {
        return;
    }

    var url = options.windowUrl;
    if (url) {
        var editEmailKey = '/sms/edit/smsId';
        if (url.indexOf(editEmailKey) > -1) {
            options.windowUrl = url.replace('smsId', mQuery('#campaignevent_properties_sms').val());
        }
    }

    return options;
};

Mautic.disabledSmsAction = function(opener) {
    if (typeof opener == 'undefined') {
        opener = window;
    }

    var sms = opener.mQuery('#campaignevent_properties_sms').val();

    var disabled = sms === '' || sms === null;

    opener.mQuery('#campaignevent_properties_editSmsButton').prop('disabled', disabled);
};

Mautic.countChar = function (textAreaName,spanName) {
    var signSelectedValue = document.querySelector('#sign-box').querySelector('#sms_sign').value;
    var signLength = 0;

    if (signSelectedValue) {
        var signText = document.querySelector('#sign-box').querySelector('a.chosen-single>span').innerText;
        signLength = signText.length + 3;
    }

    var char = document.getElementById(textAreaName).value;
    var charLength = char.length;
    // var strRegex = '((https?|ftps?):\/\/)([a-zA-Z0-9-\.{}]*[a-zA-Z0-9=}]*)(\??)([^\s\]"]+)?';
    // var result = char.match(strRegex);
    // console.log(result);
    // if (char.match(strRegex)) {
    //     charLength = charLength - result[0].length + 19;
    // }

    document.getElementById("char").innerHTML= charLength + signLength;
    document.getElementById("piece").innerHTML = Math.ceil((charLength + signLength) / 67);
}