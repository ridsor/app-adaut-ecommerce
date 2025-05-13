import "./bootstrap";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.store("globalState", {
    formattedPrice,
});

Alpine.store("cart", {
    items: Alpine.$persist([
        {
            id: 1,
            product_id: 1,
            quantity: 1,
            image: "https://themewagon.github.io/FoodMart/images/product-thumb-1.png",
            price: 15000,
            name: "Produk",
        },
        {
            id: 2,
            product_id: 2,
            quantity: 2,
            image: "https://themewagon.github.io/FoodMart/images/product-thumb-1.png",
            price: 15000,
            name: "Produk",
        },
    ]),
    update(newitem, quantity) {
        if (quantity > 0) {
            this.items = this.items.map((item) => {
                if (item.id === newitem.id) {
                    item.quantity = quantity;
                }
                return item;
            });
            this.selected = this.selected.map((item) => {
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
    total(items) {
        const result = items.reduce((acc, cur) => {
            return acc + cur.price * cur.quantity;
        }, 0);
        return result;
    },
    remove(items) {
        this.items = this.items.filter(
            (item) => !items.some((x) => x.id === item.id)
        );
        this.selected = [];
    },
    selected: [],
    isSelectedAll: false,
    selectOne(newItem) {
        const isExists = this.selected.some((item) => item.id === newItem.id);
        if (isExists) {
            this.selected = this.selected.filter(
                (item) => item.id !== newItem.id
            );
        } else {
            this.selected.push({ ...newItem });
        }
    },
    selectMany(newItems) {
        this.selected = newItems;
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
