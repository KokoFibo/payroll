import "./bootstrap";

$(document).ready(function () {
    toastr.options = {
        progressBar: true,
        timeOut: "1500",
        progressBar: true,
        positionClass: "toast-top-right",
        closeButton: true,
        preventDuplicates: true,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };
    window.addEventListener("success", (event) => {
        toastr.success(event.detail.message);
    });
    window.addEventListener("warning", (event) => {
        toastr.warning(event.detail.message);
    });

    window.addEventListener("info", (event) => {
        toastr.info(event.detail.message);
    });

    window.addEventListener("error", (event) => {
        toastr.error(event.detail.message);
    });
});

window.addEventListener("hide-form", (event) => {
    $("#update-form-modal").modal("hide");
});
window.addEventListener("update-form", (event) => {
    $("#update-form-modal").modal("show");
});
