document.addEventListener("DOMContentLoaded", () => {
    const userIcon = document.getElementById("tcUserIcon");
    const dropdown = document.getElementById("tcDropdown");
    const iconLinks = document.querySelectorAll(".icon-link");

    userIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("active");
        
        userIcon.style.transform = dropdown.classList.contains("active") 
            ? "scale(0.9)" 
            : "scale(1.1)";
        
        setTimeout(() => {
            userIcon.style.transform = "";
        }, 200);
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && !userIcon.contains(e.target)) {
            dropdown.classList.remove("active");
        }
    });

    iconLinks.forEach(icon => {
        icon.addEventListener("mousedown", () => {
            icon.style.transform = "scale(0.85)";
        });
        icon.addEventListener("mouseup", () => {
            icon.style.transform = "translateY(-2px) scale(1.1)";
        });
        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "";
        });
    });
});