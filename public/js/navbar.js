const userIcon = document.getElementById("tcUserIcon");
const dropdown = document.getElementById("tcDropdown");

userIcon.addEventListener("click",(e)=>{

e.stopPropagation();
dropdown.classList.toggle("active");

});


document.addEventListener("click",(e)=>{

if(!dropdown.contains(e.target) && !userIcon.contains(e.target)){

dropdown.classList.remove("active");

}

});


hamburger.addEventListener("click",()=>{

menu.classList.toggle("active");

});