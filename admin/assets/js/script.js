/* -------------------------------------------------------------------------- */
/*                              Msg:                                          */
/* -------------------------------------------------------------------------- */
var closeMsg = document.querySelector("body .alert .close");
var msg = document.querySelector("body .alert.alert");
closeMsg.addEventListener("click", function () {
    msg.style.display = "none";
});