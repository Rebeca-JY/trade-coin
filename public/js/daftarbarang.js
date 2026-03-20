const searchInput = document.getElementById("searchInput");
const items = document.querySelectorAll(".item-card");


searchInput.addEventListener("keyup", function(){

    let filter = searchInput.value.toLowerCase();

    items.forEach(function(item){

        let text = item.innerText.toLowerCase();

        if(text.includes(filter)){
            item.style.display = "block";
        }
        else{
            item.style.display = "none";
        }

    });

});



/* BACK BUTTON */

document.querySelector(".back-btn").addEventListener("click", function(){
    window.history.back();
});