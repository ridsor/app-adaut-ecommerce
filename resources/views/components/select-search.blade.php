@props(['field', 'options', 'selected' => ''])

<div {{ $attributes->merge(['class' => 'mt-4 flex-1']) }} x-data="data()" x-init="setInitial({{ $selected }})">
    <input type="hidden" :value="selected.value" name="{{ $field }}">
    <input class="w-1/2" type="search" x-model="search" placeholder="Click to search..." @click="showOptions"
        @search="clearSearch" />
    <div class="relative" x-show="optionsVisible" @click.away="hideOptions">
        <div class="absolute z-50 border p-2 w-1/2 overflow-y-scroll bg-gray-200 max-h-60 grid">
            <template x-for="option in filteredOptions()">
                <a @click.prevent="selectOption(option)" x-html="highlight(option.label)"
                    class="cursor-pointer hover:bg-blue-200 px-2">
                </a>
            </template>
        </div>
    </div>

    <script>
        function data() {
            return {
                optionsVisible: false,
                search: "",
                selected: {
                    label: "",
                    value: ""
                },
                clearSearch() {
                    this.selected.value = '';
                    this.selected.label = '';
                },
                setInitial(selected) {
                    selectedOption = this.options.find((option) => {
                        return option.value == selected
                    });
                    if (selectedOption != undefined) {
                        this.selectOption(selectedOption);
                    }
                },
                showOptions() {
                    this.optionsVisible = true;
                },
                hideOptions() {
                    this.optionsVisible = false;
                },
                selectOption(option) {
                    this.selected = option;
                    this.hideOptions();
                    this.search = option.label
                },
                options: {!! $options->map(function ($option, $key) {
                        return [
                            'label' => $option,
                            'value' => $key,
                        ];
                    })->values() !!},
                filteredOptions() {
                    return this.options.filter((option, i) => {
                        return option.label.toLowerCase().includes(this.search.toLowerCase());
                    });
                },
                highlight(value) {
                    var text = this.search.trim();
                    if (text == '') {
                        return value;
                    }
                    var query = new RegExp(`(${text})`, "ig");
                    return value.replace(query, '<span class="font-extrabold text-blue-600">$1</span>');
                }
            };
        }
    </script>
