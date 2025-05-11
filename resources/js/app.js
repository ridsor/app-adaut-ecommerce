import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.store("globalState", {
    formattedPrice,
});

Alpine.start();

function formattedPrice(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    })
        .format(value)
        .replace(",00", "");
}
