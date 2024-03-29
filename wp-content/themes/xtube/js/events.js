
function ajax_post(url, data) {
    jQuery.ajax({
        type: "post",
        url: url,
        data: data,
        error: function (response) {
            console.log(response);
        },
        success: function (response) {
            console.log('success:: ' + response);
        }
    });
}

function report() {
    var video_id = document.vote_form.elements['video_id'].value;
    var reason = document.report_form.elements['reason'].value;

    var data = {
        "video_id": video_id,
        "reason": reason,
        "url": ajax_var.url,
        "action": "video_report",
        "nonce": ajax_var.nonce
    }
    ajax_post2(ajax_var.url, data);
}

function vote(v) {
    var video_id = document.vote_form.elements['video_id'].value;
    var data = {
        "vote": v,
        "video_id": video_id,
        "url": ajax_var.url,
        "action": "video_vote",
        "nonce": ajax_var.nonce
    }

    ajax_post2(ajax_var.url, data);
}

function ajax_post2(url, data) {
    var url = '';
    Object.keys(data).forEach(function (key) {
        url += '&' + key + '=' + data[key];
    });

    var headers = {
        'Content-type': 'application/x-www-form-urlencoded; charset=utf-8'
    };

    fetch(ajax_var.url, {
        method: "POST",
        credentials: 'same-origin',
        headers: headers,
        body: url
    });
}