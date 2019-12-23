/* global USER_ID
 */
import 'dist/js/app.js';

import paginate from './_pagination';

import '../scss/user.scss';

import {
    checkToken,
    handleLinks,
} from './_utils';

var statusOpen = false;

$(document).ready(function() {
    $('.sidebar').hide();

    handleLinks();

    checkToken()
        .then((payload) => {
            if (USER_ID === Number(payload.id)) {
                $('#user-page-link').hide();
            } else {
                $('.editable-post').hide();
            }
        })
        .catch(() => {
            $('#user-page-link').hide();
            $('.editable-post').hide();
        });

    paginate();
});

window.toggleSidebar = function() {
    if (statusOpen) {
        $('.sidebar').hide();
        $('#btn-sidebar-open').show();
    } else {
        $('.sidebar').show();
        $('#btn-sidebar-open').hide();
    }

    statusOpen = !statusOpen;
};
