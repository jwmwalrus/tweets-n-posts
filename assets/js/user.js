/* global USER_ID
 */
import 'dist/js/app.js';

import paginate from './_pagination';

import '../scss/user.scss';

import {
    checkToken,
    handleLinks,
} from './_utils';

$(document).ready(function() {
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
