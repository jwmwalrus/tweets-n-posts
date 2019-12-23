/* global NCARDS
 */
import { range } from 'lodash';

var step = 3;
var iPage = 1;
var nPages = Math.ceil(NCARDS / 3);

export default function paginate() {
    $('.page-item-prev').on('click', pageItemPrev);
    $('.page-item-page').on('click', pageItemPage);
    $('.page-item-next').on('click', pageItemNext);

    $('#page-1').trigger('click');
}

function getCurrentRange() {
    const from = step * (iPage - 1) + 1;
    const to = from + step;
    return range(from, to);
}

function pageItemPrev() {
    if (iPage <= 1) {
        console.error('PREV IS SHOWN ON FIRST');
        return;
    }

    $(`#page-${iPage--}`).trigger('click');
}

function pageItemPage() {
    iPage = Number(this.id.split('-')[1]);
    showActiveCards();
    setActivePage(iPage);
}

function pageItemNext() {
    if (iPage <= nPages) {
        console.error('NEXT IS SHOWN ON LAST');
        return;
    }

    $(`#page-${iPage++}`).trigger('click');
}

function setActivePage(page) {
    $('.page-item-page')
        .removeClass('active')
        .filter(function() {
            return page === Number(this.id.split('-')[1]);
        })
        .addClass('active')
        .show();

    if (page === 1) {
        $('.page-item-prev').hide();
    }

    if (page === nPages) {
        $('.page-item-next').hide();
    }

    if (page - 2 >= 4) {
        $('.page-item-ellipsis-left').show();
        for (let i = 2; i < page - 2; ++i) {
            $(`#page-${i}`).hide();
        }
        $(`#page-${page - 2}`).show();
        $(`#page-${page - 1}`).show();
    } else {
        $('.page-item-ellipsis-left').hide();
        for (let i = 2; i < page; ++i) {
            $(`#page-${i}`).show();
        }
    }

    if (page + 2 <= nPages - 3) {
        $('.page-item-ellipsis-right').show();
        for (let i = nPages - 1; i > page + 2; --i) {
            $(`#page-${i}`).hide();
        }
        $(`#page-${page + 1}`).show();
        $(`#page-${page + 2}`).show();
    } else {
        $('.page-item-ellipsis-right').hide();
        for (let i = nPages - 1; i > page; --i) {
            $(`#page-${i}`).show();
        }
    }
}

function showActiveCards() {
    $('.card-paginated')
        .hide()
        .filter(function() {
            return getCurrentRange().includes(Number(this.id.split('-')[1]));
        })
        .show();
}
