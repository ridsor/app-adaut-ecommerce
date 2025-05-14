import "./bootstrap";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.store("globalState", {
    formattedPrice,
    formatNumberShort,
    formatTimeAgo,
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
        if (!isExists) {
            const id = crypto.randomUUID();
            this.items.push({
                id,
                quantity: newItem.quantity,
                product_id: newItem.product_id,
                image: newItem.image,
                name: newItem.name,
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

function formatTimeAgo(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);
  
  const intervals = {
    tahun: 31536000,
    bulan: 2592000,
    minggu: 604800,
    hari: 86400,
    jam: 3600,
    menit: 60,
    detik: 1
  };
  
  for (const [unit, secondsInUnit] of Object.entries(intervals)) {
    const interval = Math.floor(seconds / secondsInUnit);
    if (interval >= 1) {
      return `${interval} ${unit} yang lalu`;
    }
  }
  
  return 'baru saja';
}
