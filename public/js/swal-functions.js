/* INCLUDE ALL SWAL FUNCTIONS HERE */
const confirmButtonColor = "#1F5497";
const dangerButtonColor = "#d33";
const cancelButtonColor = "#566573";

function popupSimple(icon, title, text, confirmText = "OK") {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: confirmText
    });
}

function popupWithConfirmation(icon, title, text, callbackFunction, confirmText = "OK", isDanger = false) {
    let confirmColor = confirmButtonColor;

    if(isDanger) confirmColor = dangerButtonColor;
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: confirmText,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            callbackFunction();
        }
    });
}

function popupWithInput(icon, title, text, callbackFunction, confirmText = "OK") {
    Swal.fire({
        title: title,
        input: "text",
        customClass: {
        popup: "swal-popup"
        },
        inputPlaceholder: text,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        confirmButtonText: confirmText,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
        if(result.value.trim()) {
            callbackFunction(result.value);
        } else {
            popupSimple('error', "Empty input", "Please input a value");
        }
        }
    });
}

function popupToast(icon, title, ms = 2000) {
    Swal.fire({
        toast: true,
        icon: icon,
        title: title,
        position: "top-end",
        showConfirmButton: false,
        timer: ms,
        timerProgressBar: true,
    });
}

function resetMessage(seconds) {
    setTimeout(() => {
        document.getElementById("swal2-message").textContent = "";
    }, 1000 * seconds);
}
