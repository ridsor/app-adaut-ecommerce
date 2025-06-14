import axios from "axios";
import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);
import "dayjs/locale/id";
dayjs.locale("id");
window.dayjs = dayjs;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.store("globalState", {
    formatPrice,
    formatNumberShort,
});

Alpine.store("cart", {
    items: Alpine.$persist([]),
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
                    item.quantity = Number(quantity);
                }
                return item;
            });
        } else {
            this.items = this.items.map((item) => {
                if (item.id === newitem.id) {
                    item.quantity = 1;
                }
                return item;
            });
            this.selected = this.selected.map((item) => {
                if (item.id === newitem.id) {
                    item.quantity = 1;
                }
                return item;
            });
        }
    },
    add(newItem) {
        const isExists = this.items.find(
            (x) => x.product_id == newItem.product_id
        );
        const id = crypto.randomUUID();
        if (!isExists) {
            this.items.push({
                id,
                quantity: newItem.quantity,
                product_id: newItem.product_id,
                image: newItem.image,
                name: newItem.name,
                weight: newItem.weight,
                price: newItem.price,
            });
        } else {
            this.items = this.items.map((item) => {
                if (item.id === isExists.id) {
                    item.quantity += newItem.quantity;
                }
                return item;
            });
        }
        return id;
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
    selected: Alpine.$persist([]),
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

function formatPrice(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    })
        .format(value)
        .replace(",00", "");
}

function formatNumberShort(num) {
    if (num < 1000) return num.toString(); // Angka di bawah 1rb tidak diformat

    const units = ["", "rb", "jt", "m", "t"];
    const digitCount = Math.floor(Math.log10(num)) + 1;
    const unitIndex = Math.min(
        Math.floor((digitCount - 1) / 3),
        units.length - 1
    );
    const divisor = Math.pow(1000, unitIndex);
    const formattedNum = num / divisor;

    // Cek apakah ada sisa (untuk menambahkan '+')
    const hasRemainder = num % divisor !== 0;
    const symbol = hasRemainder && unitIndex > 0 ? "+" : "";

    // Format angka: bulatkan 1 desimal jika perlu (misal: 1.2rb)
    let result;
    if (formattedNum % 1 !== 0 && formattedNum < 10) {
        result = formattedNum.toFixed(1) + units[unitIndex] + symbol;
    } else {
        result = Math.floor(formattedNum) + units[unitIndex] + symbol;
    }

    return result;
}
