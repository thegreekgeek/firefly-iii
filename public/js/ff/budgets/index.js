/*
 * index.js
 * Copyright (c) 2017 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III.  If not, see <http://www.gnu.org/licenses/>.
 */

/** global: spent, budgeted, available, currencySymbol, budgetIndexUri, updateIncomeUri, periodStart, periodEnd, budgetAmountUri, accounting */

/**
 *
 */
$(function () {
    "use strict";

    $('.updateIncome').on('click', updateIncome);
    $('.infoIncome').on('click', infoIncome);

    /*
     On start, fill the "spent"-bar using the content from the page.
     */
    drawSpentBar();
    drawBudgetedBar();

    /*
     When the input changes, update the percentages for the budgeted bar:
     */
    $('input[type="number"]').on('input', updateBudgetedAmounts);

    //
    $('.selectPeriod').change(function (e) {
        var sel = $(e.target).val();
        if (sel !== "x") {
            var newUri = budgetIndexUri.replace("REPLACE", sel);
            window.location.assign(newUri);
        }
    });

});

function drawSpentBar() {
    "use strict";
    if ($('.spentBar').length > 0) {
        var overspent = spent > budgeted;
        var pct;

        if (overspent) {
            // draw overspent bar
            pct = (budgeted / spent) * 100;
            $('.spentBar .progress-bar-warning').css('width', pct + '%');
            $('.spentBar .progress-bar-danger').css('width', (100 - pct) + '%');
        } else {
            // draw normal bar:
            pct = (spent / budgeted) * 100;
            $('.spentBar .progress-bar-info').css('width', pct + '%');
        }
    }
}

function drawBudgetedBar() {
    "use strict";

    if ($('.budgetedBar').length > 0) {
        var budgetedMuch = budgeted > available;

        // recalculate percentage:

        var pct;
        if (budgetedMuch) {
            // budgeted too much.
            pct = (available / budgeted) * 100;
            $('.budgetedBar .progress-bar-warning').css('width', pct + '%');
            $('.budgetedBar .progress-bar-danger').css('width', (100 - pct) + '%');
            $('.budgetedBar .progress-bar-info').css('width', 0);
        } else {
            pct = (budgeted / available) * 100;
            $('.budgetedBar .progress-bar-warning').css('width', 0);
            $('.budgetedBar .progress-bar-danger').css('width', 0);
            $('.budgetedBar .progress-bar-info').css('width', pct + '%');
        }

        $('#budgetedAmount').html(currencySymbol + ' ' + budgeted.toFixed(2));
    }
}

/**
 *
 * @param e
 */
function updateBudgetedAmounts(e) {
    "use strict";
    var target = $(e.target);
    var id = target.data('id');

    var value = target.val();
    var original = target.data('original');
    var difference = value - original;

    var spentCell = $('td[class="spent"][data-id="' + id + '"]');
    var leftCell = $('td[class="left"][data-id="' + id + '"]');
    var spentAmount = parseFloat(spentCell.data('spent'));
    var newAmountLeft = spentAmount + parseFloat(value);
    var amountLeftString = accounting.formatMoney(newAmountLeft);
    if (newAmountLeft < 0) {
        leftCell.html('<span class="text-danger">' + amountLeftString + '</span>');
    }
    if (newAmountLeft > 0) {
        leftCell.html('<span class="text-success">' + amountLeftString + '</span>');
    }
    if (newAmountLeft === 0.0) {
        leftCell.html('<span style="color:#999">' + amountLeftString + '</span>');
    }

    if (difference !== 0) {
        // add difference to 'budgeted' var
        budgeted = budgeted + difference;

        // update original:
        target.data('original', value);
        // run drawBudgetedBar() again:
        drawBudgetedBar();

        // send a post to Firefly to update the amount:
        var newUri = budgetAmountUri.replace("REPLACE", id);
        $.post(newUri, {amount: value, start: periodStart, end: periodEnd}).done(function (data) {
            // update the link if relevant:
            if (data.repetition > 0) {
                $('.budget-link[data-id="' + id + '"]').attr('href', 'budgets/show/' + id + '/' + data.repetition);
            } else {
                $('.budget-link[data-id="' + id + '"]').attr('href', 'budgets/show/' + id);
            }
        });
    }
}

/**
 *
 * @returns {boolean}
 */
function updateIncome() {
    "use strict";
    $('#defaultModal').empty().load(updateIncomeUri, function () {
        $('#defaultModal').modal('show');
    });

    return false;
}

function infoIncome() {
    $('#defaultModal').empty().load(infoIncomeUri, function () {
        $('#defaultModal').modal('show');
    });

    return false;
}
