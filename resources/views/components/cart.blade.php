<div id="cart" x-data>
    <template x-if="($store.cart.items.length > 0)">
        <div class="d-flex flex-column">
            <template x-for="(item, index) in $store.cart.items" :key="index">
                <div class="cart-item">
                    <div class="d-flex gap-2">
                        <div class="cart-image ratio ratio-1x1" style="width: 80px">
                            <img :src="item.image" style="background-position: center"
                                class="h-100 object-fit-contain" />
                        </div>
                        <div class="cart-body" style="flex: 1">
                            <div class="cart-title" x-text="item.name"></div>
                            <div class="cart-title fw-semibold text-primary mb-2"
                                x-text="$store.globalState.formattedPrice(item.price)"></div>
                            <div class="cart-input d-flex rounded-2 overflow-hidden" style="width:fit-content">
                                <button class="p-0 minus border-0" @click="$store.cart.update(item, item.quantity - 1)">
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 30px; height: 30px">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </button>
                                <input type="number" class="rounded-1 border-0 px-2 text-center cart-input"
                                    style="width: 50px; font-size: 14px" :value="item.quantity" />
                                <button class="p-0 plus border-0" @click="$store.cart.update(item, item.quantity + 1)">
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 30px; height: 30px">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="cart-select d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
    <template x-if="!($store.cart.items.length > 0)">
        <div class="empty">
            <div class="p-3 text-center fs-5 fw-medium">Tidak ada keranjang</p>
            </div>
    </template>
</div>
