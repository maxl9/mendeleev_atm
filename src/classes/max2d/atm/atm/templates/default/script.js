document.addEventListener("click", atmClickHandler);
document.addEventListener("change", atmChangeHandler);
document.addEventListener("keydown", atmKeydownHandler);

function atmKeydownHandler(event){
    let element = event.target;
    if(event.key === "Backspace") return false;
    if(element.classList.contains("js-amount")){
        const isNumber = /^[0-9]$/i.test(event.key);
        if(!isNumber) event.preventDefault();
    }
    if(element.classList.contains("js-digital")){
        const isNumber = /^[0-9]$/i.test(event.key)
        if(!isNumber) event.preventDefault();
    }
}

function atmChangeHandler(event){
    let element = event.target;
    if(element.classList.contains("js-amount")){
        let value = parseInt(element.value);
        if(value <= 100){
            value = 100;
        }else if(value >= 100000){
            value = 100000;
        }else{
            // Округление до кратного 100
            let remains = value % 100;
            if(remains < 50){
                value -= remains;
            }else{
                value += 100 - remains;
            }
        }
        element.value = value;
    }
    if(element.classList.contains("js-digital")){
        let value = parseInt(element.value.replace(/[^0-9]+/g, ""));
        if(typeof value === "number"){
            element.value = value;
        }else{
            element.value = "";
        }
    }
}
function atmClickHandler(event){
    let element = event.target,
        elementParent = element.parentNode;

    if(element.classList.contains("js-atm-navigation")){
        event.preventDefault();
        atmNavigation(element);
    }
    if(elementParent.classList.contains("js-atm-navigation")){
        event.preventDefault();
        atmNavigation(elementParent);
    }

    if(element.classList.contains("js-reset")){
        atmFormReset(elementParent);
    }
    if(elementParent.classList.contains("js-reset")){
        atmFormReset(elementParent);
    }
}
function atmFormReset(element){
    let form = element.closest("form");
    form.querySelector(".atm-screen__layout.active").classList.remove("active");
    form.querySelector(".atm-screen__layout[data-layout-id=\"start\"]").classList.add("active");
    form.classList.remove("authorized");
    form.setAttribute("data-user-id", "");
    form.querySelector("input[name=\"ACTION\"]").value = "START";
}

function atmAjax(form){
    let action = form.getAttribute("action"),
        method = form.getAttribute("method"),
        xhr = new XMLHttpRequest();

    xhr.open(method, action);
    xhr.responseType = "json";
    xhr.send(new FormData(form));
    xhr.onload = function(){
        let response = xhr.response,
            inputAction = form.querySelector("input[name=\"ACTION\"]");
        if(response.STATUS === "OK"){
            if(inputAction.value.toLowerCase() === "withdrawal"){
                form.querySelector("input[name=\"WITHDRAWAL_AMOUNT\"]").value = "";
            }
            if(typeof response.STEP !== "undefined"){
                form.querySelector(".atm-screen__layout.active").classList.remove("active");
                form.querySelector(`.atm-screen__layout[data-layout-id="${response.STEP.toLowerCase()}"`).classList.add("active");
                inputAction.value = response.STEP;
            }

            if(typeof response.AUTH !== "undefined" && response.AUTH === "Y"){
                form.classList.add("authorized");
                form.setAttribute("data-user-id", response.USER_ID);

                if(typeof response.BALANCE !== "undefined" && response.BALANCE !== ""){
                    document.querySelectorAll(`form.js-atm[data-user-id="${response.USER_ID}"] .prop-balance`).forEach(function(element){
                        element.setAttribute("data-value", response.BALANCE);
                    });
                }
            }
            if(typeof response.CONSOLE !== "undefined"){
                console.log(response.CONSOLE);
            }
        }

        if(response.STATUS === "ERROR"){}

        if(typeof response.ALERT !== "undefined" && response.ALERT !== "" ){
            alert(response.ALERT);
        }
    };
}

function atmNavigation(button){
    let form = button.closest("form"),
        direction = button.getAttribute("data-nav-direction"),
        action = button.getAttribute("data-form-action");
    if(direction !== null){
        if(direction === "next"){
            let bAllowSubmit = true,
                layout = form.querySelector(".atm-screen__layout.active"),
                inputs = layout.querySelectorAll("input[required]");

            if(action !== null){
                form.querySelector("input[name=\"ACTION\"]").value = action;
            }
            if(inputs.length > 0){
                for(const input of inputs){
                    if(input.value.trim() === ""){
                        bAllowSubmit = false;
                        input.classList.add("input-error");
                        input.setAttribute("data-error-text", "Ошибка в заполнении");
                        input.addEventListener("change", function(){
                            if(input.value.trim() === ""){
                                input.classList.add("input-error");
                                input.setAttribute("data-error-text", "Ошибка в заполнении");
                            }else{
                                input.classList.remove("input-error");
                                input.setAttribute("data-error-text", "");
                            }
                        });
                    }
                }
            }

            if(bAllowSubmit === true) atmAjax(form);
        }
    }
}