import 'dist/js/app.js';

import paginate from './_pagination';

import '../scss/home.scss';

import {
    handleLinks,
} from './_utils';

$(document).ready(function() {
    handleLinks();
    $('#home-link').hide();
    paginate();
});
