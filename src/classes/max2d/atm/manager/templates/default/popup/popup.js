document.addEventListener("click", popupClickHandler);

function popupClickHandler(){
    let element = event.target,
        elementParent = element.parentNode;

    if(element.classList.contains("js-popup-close")){
        event.preventDefault();
        atmNavigation(element);
    }
    if(elementParent.classList.contains("js-popup-close")){
        event.preventDefault();
        popupClose(elementParent);
    }
}

function popupClose(button){
    let popup = button.closest(".popup");
    popup.classList.remove("visible");
    setTimeout(() => {
        popup.classList.remove("active");
    }, 240);
}