function numFormatter(value, row) {
    return (value === null ? null : parseFloat(value).formatMoney(0));
}

function numFormatter2(value, row) {
    return (value === null ? null : parseFloat(value).formatMoney(2));
}

function numFormatter4(value, row) {
    return (value === null ? null : parseFloat(value).formatMoney(4));
}

