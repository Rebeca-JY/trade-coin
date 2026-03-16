function formatRupiah(number){
    return "" + number.toLocaleString("id-ID") + " Points";
}

function getPrice(text){
    return parseInt(text.replace(/[^0-9]/g,"")) || 0;
}

function updateCart(){

    let cartItems = document.querySelectorAll(".cart-item");
    let grandTotal = 0;

    cartItems.forEach(item => {

        let checkbox = item.querySelector(".item-check");
        let priceText = item.querySelector(".unit-price").innerText;
        let price = getPrice(priceText);

        let qty = parseInt(item.querySelector(".qty-num").innerText);

        let total = price * qty;

        item.querySelector(".item-total").innerText = formatRupiah(total);

        if(checkbox && checkbox.checked){
            grandTotal += total;
        }

    });

    document.querySelector(".grand-total").innerText =
        "Total : " + formatRupiah(grandTotal);
}

document.querySelectorAll(".cart-item").forEach(item => {

    let qtyBtns = item.querySelectorAll(".qty-btn");
    let minusBtn = qtyBtns[0];
    let plusBtn = qtyBtns[1];

    let qtyNum = item.querySelector(".qty-num");
    let deleteBtn = item.querySelector(".delete-btn");
    let checkbox = item.querySelector(".item-check");

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

    checkbox.addEventListener("change", updateCart);

});

updateCart();