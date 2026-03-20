document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.querySelector(".cart-container");
    const grandTotalElement = document.querySelector(".grand-total");

    const formatPoints = (num) => {
        return num.toLocaleString("id-ID") + " Points";
    };

    const parsePoints = (text) => {
        return parseInt(text.replace(/[^0-9]/g, "")) || 0;
    };

    const updateCart = () => {
        let totalAll = 0;
        const items = document.querySelectorAll(".cart-item");

        items.forEach(item => {
            const isChecked = item.querySelector(".item-check").checked;
            const price = parsePoints(item.querySelector(".unit-price").innerText);
            const qty = parseInt(item.querySelector(".qty-num").innerText);
            const subtotal = price * qty;
            
            item.querySelector(".item-total").innerText = formatPoints(subtotal);

            if (isChecked) {
                totalAll += subtotal;
            }
        });

        grandTotalElement.innerHTML = `Total : <span>${formatPoints(totalAll)}</span>`;
    };

    cartContainer.addEventListener("click", (e) => {
        const target = e.target;
        const item = target.closest(".cart-item");
        if (!item) return;

        const qtyNum = item.querySelector(".qty-num");
        let currentQty = parseInt(qtyNum.innerText);

        if (target.classList.contains("qty-btn") || target.parentElement.classList.contains("qty-btn")) {
            if (target.innerText === "+" || target.textContent === "+") {
                qtyNum.innerText = currentQty + 1;
            } else if (target.innerText === "-" || target.textContent === "-") {
                if (currentQty > 1) qtyNum.innerText = currentQty - 1;
            }
            updateCart();
        }

        if (target.closest(".delete-btn")) {
            item.style.opacity = "0";
            item.style.transform = "translateX(20px)";
            setTimeout(() => {
                item.remove();
                updateCart();
            }, 300);
        }
    });

    cartContainer.addEventListener("change", (e) => {
        if (e.target.classList.contains("item-check")) {
            updateCart();
        }
    });

    updateCart();
});