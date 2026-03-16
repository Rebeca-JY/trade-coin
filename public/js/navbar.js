const userIcon = document.getElementById("userIcon");
const dropdown = document.getElementById("userDropdown");

userIcon.addEventListener("click", () => {

    if(dropdown.style.display === "flex"){
        dropdown.style.display = "none";
    }else{
        dropdown.style.display = "flex";
    }

});

document.addEventListener("click", function(e){

    if(!userIcon.contains(e.target) && !dropdown.contains(e.target)){
        dropdown.style.display = "none";
    }

});