document.addEventListener("DOMContentLoaded", ATMManagerInit);

function ATMManagerInit(){
    let panelButtonToggle = document.querySelectorAll(".js-settings-toggle"),
        panelButtonOpen = document.querySelectorAll(".js-settings-open"),
        panelButtonClose = document.querySelectorAll(".js-settings-close"),
        timers = {
            settingsToggle: setTimeout(() => {}, 3600000)
        };

    for(let button of panelButtonToggle){
        button.addEventListener("click", settingsToggle);
    }
    for(let button of panelButtonOpen){
        button.addEventListener("click", settingsOpen);
    }
    for(let button of panelButtonClose){
        button.addEventListener("click", settingsClose);
    }

    function settingsToggle(){
        let panel = document.querySelector(".atm-manager__settings");
        if(panel !== null){
            if(panel.classList.contains("active")){
                settingsClose()
            }else{
                settingsOpen();
            }
        }
    }
    function settingsOpen(){
        let panel = document.querySelector(".atm-manager__settings");
        if(panel !== null){
            document.body.style.overflow = "hidden";
            panel.classList.add("active");
            timers.settingsToggle = setTimeout(() => {
                panel.classList.add("visible");
                clearTimeout(timers.settingsToggle);
            }, 100);
        }
    }

    function settingsClose(){
        let panel = document.querySelector(".atm-manager__settings");
        if(panel !== null){
            document.body.style.overflow = "";
            panel.classList.remove("visible");
            timers.settingsToggle = setTimeout(() => {
                panel.classList.remove("active");
                clearTimeout(timers.settingsToggle);
            }, 400);
        }
    }
}