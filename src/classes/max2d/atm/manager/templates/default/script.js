document.addEventListener("submit", managerAjaxHandler);
document.addEventListener("change", managerChangeHandler);

function managerChangeHandler(event){
    let element = event.target,
        form = element.closest("form");

    if(form !== null && form.classList.contains("js-choose-atm")){
        event.preventDefault();
        managerChooseATM(form);
    }
}
function managerAjaxHandler(event){
    let element = event.target;
    if(element.classList.contains("js-form-ajax")){
        event.preventDefault();
    }
}

function managerAjaxRun(form){
    let action = form.getAttribute("action"),
        method = form.getAttribute("method"),
        xhr = new XMLHttpRequest();

    xhr.open(method, action);
    xhr.send(new FormData(form));
    xhr.onload = function(){
        let response = xhr.response,
            emptyATMMessage = document.querySelector(".atm__empty");

        if(emptyATMMessage !== null && response.trim() !== ""){
            let responseHtml = document.createElement("div");
            responseHtml.innerHTML = response;
            emptyATMMessage.before(responseHtml.querySelector(".atm-container"));
        }
    };
}

function managerChooseATM(form){
    let formData = new FormData(form),
        id = formData.get("ID"),
        active = formData.get("ACTIVE");

    managerAjaxRun(form);
    if(active === null){
        let atm = document.querySelector(`.atm-container[data-atm-id="${id}"]`);
        if(atm !== null) atm.remove();
    }
}