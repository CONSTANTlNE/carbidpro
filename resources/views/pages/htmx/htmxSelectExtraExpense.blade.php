

<div class="container" id="alpinehtml" hx-swap-oob="true">
    <div x-data="$store.balanceAccountingStore" class="mt-4">
        <div class="d-flex" style="gap:10px">
            <p x-text="'Number of items: ' + balance_accounting.length"></p>

            <!-- Display the total of values -->
            <p>Total Value: <span x-text="calculateTotal()"></span></p>
        </div>

        <!-- Repeater Fields in One Row -->
        <template x-for="(item, index) in balance_accounting" :key="index">
            <div class="row repeater-item mt-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" :name="`balance_accounting[${index}][name]`"
                           x-model="item.name"
                           class="name-autocomplete form-control"
                           placeholder="Enter item name">
                </div>
                <div class="col-md-4">
                    <input type="number"
                           :name="`balance_accounting[${index}][value]`"
                           x-model.number="item.value" class="form-control"
                           placeholder="Enter item value">
                </div>
                {{-- DATE--}}
                <div style="display: none" class="col-md-4">
                    <input type="date" :name="`balance_accounting[${index}][date]`"
                           x-model="item.date" class="form-control"
                           x-init="item.date = item.date || '{{ now()->format('Y-m-d') }}'">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger"
                            @click="confirmRemoveField(index)">Remove
                    </button>

                </div>
            </div>
        </template>

        <!-- Add Button -->
        <button type="button" class="btn btn-primary mt-3" @click="addField">
            Add Another Item
        </button>

        <div x-data="{
                                            payed: '0',
                                            get amountDue() {
                                                return calculateTotal() - parseFloat(this.payed || 0);
                                            },
                                            validateNumber() {
                                                this.payed = this.payed.replace(/[^0-9.]/g, ''); // Only keep numbers and a single decimal point
                                            }
                                        }">
            <div class="row">
                <div class="col-md-4">
                    <label for="allcost">All Cost</label>
                    <input type="text" name="total_cost" id="allcost"
                           class="form-control" x-bind:value="calculateTotal()"
                           readonly>
                </div>

                <div class="col-md-4">
                    <label for="payed">Payed</label>
                    <input type="text" name="payed"
                           id="payed" class="form-control" x-model="payed"
                           x-on:input="validateNumber" required>
                </div>

                <div class="col-md-4">
                    <label for="amountDue">Amount Due</label>
                    <input type="text" name="balance" id="amountDue"
                           class="form-control" x-bind:value="amountDue" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="alpinetarget"  hx-swap-oob="true">

    // document.addEventListener('alpine:init', () => {
    //
    // });

    Alpine.store('balanceAccountingStore', {

        balance_accounting: @json($balanceAccounting ?? [['name' => '', 'value' => 0,'date'=>'']]), // Load existing data or default


        addField() {

            initializeAutocomplete();

            this.balance_accounting.push({
                name: '',
                value: 0,
                date: ''
            });
        },

        // New method with confirmation
        confirmRemoveField(index) {
            if (confirm("Are you sure you want to remove this item?")) {
                this.removeField(index);
            }
        },

        removeField(index) {
            this.balance_accounting.splice(index, 1);
        },

        calculateTotal() {
            return this.balance_accounting.reduce((total, item) => {
                return total + (parseFloat(item.value) || 0);
            }, 0);
        },

        // Update or add Shipping cost
        updateShippingCost(shippingCost) {
            let shippingFound = false;

            for (let i = 0; i < this.balance_accounting.length; i++) {
                if (this.balance_accounting[i].name === 'Shipping cost') {
                    this.balance_accounting[i].value = shippingCost;
                    shippingFound = true;
                    break;
                }
            }

            if (!shippingFound) {
                this.balance_accounting.push({
                    name: 'Shipping cost',
                    value: shippingCost
                });
            }
        }
    });
</script>
