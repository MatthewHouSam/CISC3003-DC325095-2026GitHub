/* define functions here */

function calculateTotal(quantity, price) {
    return quantity * price;
}

function calculateTax(subtotal, rate) {
    return subtotal * rate;
}

function calculateShipping(subtotal, threshold) {
    return subtotal > threshold ? 0 : 40;
}

function calculateGrandTotal(subtotal, tax, shipping) {
    return subtotal + tax + shipping;
}

function outputCurrency(num) {
    return '$' + num.toFixed(2);
}

function outputCartRow(file, title, quantity, price, total) {
    document.write("<tr>");
    document.write('<td><img src="images/' + file + '"></td>');
    document.write("<td>" + title + "</td>");
    document.write('<td class="center">' + quantity + "</td>");
    document.write('<td class="right">' + outputCurrency(price) + "</td>");
    document.write('<td class="right">' + outputCurrency(total) + "</td>");
    document.write("</tr>");
}