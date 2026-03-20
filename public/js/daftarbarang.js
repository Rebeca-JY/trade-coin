document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const itemCards = document.querySelectorAll(".item-card");
    const backBtn = document.querySelector(".back-btn");

 
    searchInput.addEventListener("input", () => {
        const keyword = searchInput.value.toLowerCase().trim();

        itemCards.forEach(card => {
     
            const title = card.querySelector("h3").textContent.toLowerCase();
            const info = card.textContent.toLowerCase();

            if (title.includes(keyword) || info.includes(keyword)) {
                card.style.display = ""; 
            } else {
                card.style.display = "none"; 
            }
        });
    });
    if (backBtn) {
        backBtn.addEventListener("click", () => {
            window.history.back();
        });
    }
});