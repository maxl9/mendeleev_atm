document.addEventListener("DOMContentLoaded", () => {
    ATMManagerSettingsInit();
});

function ATMManagerSettingsInit(){
    let atmManagers = document.querySelectorAll(".atm-manager");
    for(let atmManager of atmManagers){
        atmManager.addEventListener("click", function(event){
            let elementClicked = event.target;
            // console.log(elementClicked);
            if(elementClicked.classList.contains("js-settings-open")){
                let settings = atmManager.querySelector(".atm-manager__settings");
                settings.classList.add("active");
                console.log(settings);
                setTimeout(() => {
                    settings.classList.add("visible");
                }, 1200);
            }
        });
    }
}
