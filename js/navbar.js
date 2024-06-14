function updateCart(response) {
    var totalPrice = 0;
    var totalItems = 0;
    $.each(JSON.parse(response), function(index2, obj2) {
        console.log(parseInt(obj2.price), " ", parseInt(obj2.quantity));
        totalPrice += parseInt(obj2.price) * parseInt(obj2.quantity);
        totalItems += parseInt(obj2.quantity);
    });
    $(".totalItems").text(totalItems)
    $('#cart-count').text(totalItems);
    $(".grandTotal").text(totalPrice)
}

