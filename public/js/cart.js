function formatRupiah(number){
    return "Rp. " + number.toLocaleString("id-ID") + ",00";
}

function getPrice(text){
    return parseInt(text.replace(/[^0-9]/g,""));
}

function updateCart(){

    let cartItems = document.querySelectorAll(".cart-item");
    let grandTotal = 0;

    cartItems.forEach(item => {

        let unitPriceText = item.querySelector(".unit-price").innerText;
        let unitPrice = getPrice(unitPriceText);

        let qty = parseInt(item.querySelector(".qty-num").innerText);

        let itemTotal = unitPrice * qty;

        item.querySelector(".item-total").innerText = formatRupiah(itemTotal);

        grandTotal += itemTotal;

    });

    document.querySelector(".grand-total").innerText =
        "Total : " + formatRupiah(grandTotal);
}


document.querySelectorAll(".cart-item").forEach(item => {

    let minusBtn = item.querySelector(".qty-btn:nth-child(1)");
    let plusBtn = item.querySelector(".qty-btn:nth-child(3)");
    let qtyNum = item.querySelector(".qty-num");
    let deleteBtn = item.querySelector(".delete-btn");

    plusBtn.addEventListener("click", () => {

        qtyNum.innerText = parseInt(qtyNum.innerText) + 1;
        updateCart();

    });

    minusBtn.addEventListener("click", () => {

        let qty = parseInt(qtyNum.innerText);

        if(qty > 1){
            qtyNum.innerText = qty - 1;
            updateCart();
        }

    });

    deleteBtn.addEventListener("click", () => {

        item.remove();
        updateCart();

    });

});

updateCart();