<div id="cart" x-data class="relative">
    <div x-show="$store.cart.selected.length" x-transition x-transition.duration.300ms x-transition.opacity>
        <div class="d-flex justify-content-between mb-1 align-items-center" style="flex:1">
            <div style="font-size: 14px" class="text-secondary">
                <span x-text="$store.cart.selected.length"></span> produk terpilih
            </div>
            <div>
                <button @click="$store.cart.remove($store.cart.selected)" class="btn text-primary"
                    style="font-size: 14px">Hapus</button>
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 end-0 start-0 border-top z-3 bg-light">
        <div class="d-flex">
            <div class="d-flex align-items-center pe-2">
                <div class="p-2">
                    <div class="form-check d-flex justify-content-center align-content-center">
                        <input class="form-check-input"
                            x-bind:checked="$store.cart.items.length !== 0 && $store.cart.items.length === $store.cart.selected.length"
                            type="checkbox" :disabled="!($store.cart.items.length > 0)"
                            @change="$event.target.checked ? $store.cart.selectMany($store.cart.items) : $store.cart.selectMany([])">
                    </div>
                </div>
                <div style="font-size: 13px">Semua</div>
            </div>
            <div class="d-flex justify-content-end" style="flex: 1">
                <div class="d-flex align-items-center gap-1 px-2">
                    <div>
                        <div class="total-price fw-medium" style="font-size: 14px"
                            x-text="$store.globalState.formattedPrice($store.cart.total($store.cart.selected))"></div>
                    </div>
                    <div>
                        <button class="btn btn-primary" :disabled="$store.cart.selected.length < 1">
                            <span style="font-size: 14px">
                                Checkout (<span x-text="$store.cart.selected.length">0</span>)
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <template x-if="($store.cart.items.length > 0)">
        <div class="d-flex flex-column gap-2">
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
                            <div class="cart-input d-flex border rounded-2 overflow-hidden" style="width:fit-content">
                                <button class="p-0 minus border-0"
                                    @click="$store.cart.update(item, Number(item.quantity - 1))"
                                    :disabled="item.quantity < 2">
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 30px; height: 30px">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </button>
                                <input type="number" class="rounded-1 border-0 px-2 text-center"
                                    style="width: 50px; font-size: 14px; outline: none" :value="item.quantity"
                                    @change="$store.cart.update(item, Number($event.target.value))" />
                                <button class="p-0 plus border-0"
                                    @click="$store.cart.update(item, Number(item.quantity + 1))">
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 30px; height: 30px">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="cart-select d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    x-bind:checked="$store.cart.selected.some(x => x.id === item.id)"
                                    @change="$store.cart.selectOne(item)">
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
