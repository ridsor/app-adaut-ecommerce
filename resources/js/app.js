import "./bootstrap";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.store("globalState", {
    formattedPrice,
});

Alpine.store("cart", {
    select: Alpine.$persist([]),
    items: Alpine.$persist([
        {
            product_id: 1,
            quantity: 1,
            image: "https://themewagon.github.io/FoodMart/images/product-thumb-1.png",
            price: 15000,
            name: "Produk",
        },
    ]),
    total: Alpine.$persist(0),
    update(newitem, quantity) {
        if (quantity > 0) {
            this.items = this.items.map((item) => {
                if (item.id === newitem.id) {
                    item.quantity = quantity;
                }
                return item;
            });
        }
    },
    add(newItem) {
        this.items.push({ ...newItem });
    },
    total(item) {
        const result = this.items.reduce((acc, cur) => {
            acc + cur.price * cur.quantity;
        }, 0);
        return result;
    },
    remove(id) {
        const cartItem = this.items.find((item) => item.id === newItem.id);
        if (cartItem) {
            this.items = this.items.filter((item) => {
                if (item.id !== id) {
                    this.total -= item.price * item.quantity;
                    return false;
                }
                return true;
            });
        }
    },
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
