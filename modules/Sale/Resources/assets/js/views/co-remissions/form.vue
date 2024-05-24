<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                        </div>
                        <div class="row mt-4">

                            <div class="col-lg-6 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label">
                                        Cliente
                                        <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                    </label>
                                    <el-select v-model="form.customer_id" filterable remote class="border-left rounded-left border-info" popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteCustomers"
                                        :loading="loading_search"
                                        @change="changeCustomer">

                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" :picker-options="datEmision"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_expiration}">
                                    <label class="control-label">Fec. Vencimiento</label>
                                    <el-date-picker v-model="form.date_expiration" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_expiration" v-text="errors.date_expiration[0]"></small>
                                </div>
                            </div>


                            <div class="col-lg-2" v-show="form.payment_form_id == 2">
                                <div class="form-group" :class="{'has-danger': errors.time_days_credit}">
                                    <label class="control-label">Plazo Credito</label>
                                    <el-input v-model="form.time_days_credit"></el-input>
                                    <small class="form-control-feedback" v-if="errors.time_days_credit" v-text="errors.time_days_credit[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.currency_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_id" @change="changeCurrencyType" filterable>
                                        <el-option v-for="option in currencies" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_id" v-text="errors.currency_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.payment_form_id}">
                                    <label class="control-label">Forma de pago</label>
                                    <el-select v-model="form.payment_form_id" filterable>
                                        <el-option v-for="option in payment_forms" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payment_form_id" v-text="errors.payment_form_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.payment_method_id}">
                                    <label class="control-label">Medio de pago</label>
                                    <el-select v-model="form.payment_method_id" filterable>
                                        <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Observaciones</label>
                                    <el-input
                                            type="textarea"
                                            autosize
                                            :rows="1"
                                            v-model="form.observation">
                                    </el-input>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="font-weight-bold">Descripción</th>
                                                <th class="text-center font-weight-bold">Unidad</th>
                                                <th class="text-right font-weight-bold">Cantidad</th>
                                                <th class="text-right font-weight-bold">Precio Unitario</th>
                                                <th class="text-right font-weight-bold">Subtotal</th>
                                                <th class="text-right font-weight-bold">Descuento</th>
                                                <th class="text-right font-weight-bold">Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="form.items.length > 0">
                                            <tr v-for="(row, index) in form.items" :key="index">
                                                <td>{{index + 1}}</td>
                                                <td>{{row.item.name}}
                                                    {{row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''}}
                                                    <br/>
                                                    <small>{{row.tax.name}}</small>
                                                </td>
                                                <td class="text-center">{{row.item.unit_type.name}}</td>

                                                <td class="text-right">{{row.quantity}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.quantity"></el-input-number> </td> -->

                                                <td class="text-right">{{ratePrefix()}} {{getFormatUnitPriceRow(row.price)}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.unit_price"></el-input-number> </td> -->


                                                <td class="text-right">{{ratePrefix()}} {{row.subtotal}}</td>
                                                <td class="text-right">{{ratePrefix()}} {{ row.discount }}</td>
                                                <td class="text-right">{{ratePrefix()}} {{row.total}}</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click="ediItem(row, index)" ><span style='font-size:10px;'>&#9998;</span> </button>

                                                </td>
                                            </tr>
                                            <tr><td colspan="9"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="clickAddItemInvoice">+ Agregar Producto</button>
                                </div>
                            </div>

                            <div class="col-md-12" style="display: flex; flex-direction: column; align-items: flex-end;" v-if="form.items.length > 0">
                                <table>

                                    <tr>
                                        <td>TOTAL VENTA</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ form.sale }}</td>
                                    </tr>
                                    <tr >
                                        <td>TOTAL DESCUENTO (-)</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ form.total_discount }}</td>
                                    </tr>
                                    <template v-for="(tax, index) in form.taxes">
                                        <tr v-if="((tax.total > 0) && (!tax.is_retention))" :key="index">
                                            <td >
                                                {{tax.name}}(+)
                                            </td>
                                            <td>:</td>
                                            <td class="text-right">{{ratePrefix()}} {{Number(tax.total).toFixed(2)}}</td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td>SUBTOTAL</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ form.subtotal }}</td>
                                    </tr>

                                    <template v-for="(tax, index) in form.taxes">
                                        <tr v-if="((tax.is_retention) && (tax.apply))" :key="index">

                                            <td>{{tax.name}}(-)</td>
                                            <td>:</td>
                                            <!-- <td class="text-right">
                                                {{ratePrefix()}} {{Number(tax.retention).toFixed(2)}}
                                            </td> -->
                                            <td class="text-right" width=35%>
                                                <el-input v-model="tax.retention" readonly >
                                                    <span slot="prefix" class="c-m-top">{{ ratePrefix() }}</span>
                                                    <i slot="suffix" class="el-input__icon el-icon-delete pointer"  @click="clickRemoveRetention(index)"></i>
                                                    <!-- <el-button slot="suffix" icon="el-icon-delete" @click="clickRemoveRetention(index)"></el-button> -->
                                                </el-input>
                                            </td>
                                        </tr>
                                    </template>

                                </table>

                                <template>
                                    <h3 class="text-right"><b>TOTAL: </b>{{ratePrefix()}} {{ form.total }}</h3>
                                </template>
                            </div>

                        </div>

                    </div>


                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                    </div>
                </form>
            </div>

        <document-form-item :showDialog.sync="showDialogAddItem"
                           :recordItem="recordItem"
                           :isEditItemNote="false"
                           :currency-type-id-active="form.currency_id"
                           :currency-type-symbol-active="ratePrefix()"
                           :typeUser="typeUser"
                           :configuration="configuration"
                           @add="addRow"></document-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"
                       :input_person="input_person"
                       :type_document_id = form.type_document_id></person-form>

        <document-options :showDialog.sync="showDialogOptions"
                            :recordId="documentNewId"
                            :showDownload="true"
                            :showClose="false"></document-options>


        </div>
    </div>
</template>

<style>

.c-m-top{
    margin-top: 4.5px !important;
}

.pointer{
    cursor: pointer;
}

.input-custom{
    width: 50% !important;
}

.el-textarea__inner {
    height: 65px !important;
    min-height: 65px !important;
}

</style>
<script>
    import DocumentFormItem from './partials/item.vue'
    import PersonForm from '@views/persons/form.vue'
    // import DocumentOptions from '../documents/partials/options.vue'
    import {functions, exchangeRate} from '@mixins/functions'
    // import {calculateRowItem} from '../../../helpers/functions'
    import DocumentOptions from './partials/options.vue'

    export default {
        props: ['typeUser', 'configuration'],
        components: {PersonForm, DocumentFormItem, DocumentOptions},
        mixins: [functions, exchangeRate],
        data() {
            return {
                showDialogOrderReference: false,
                datEmision: {
                  disabledDate(time) {
                    return time.getTime() > moment();
                  }
                },
                input_person:{},
                company:{},
                is_client:false,
                recordItem: null,
                resource: 'co-remissions',
                showDialogAddItem: false,
                showDialogAddRetention: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                currencies: [],
                all_customers: [],
                payment_methods: [],
                payment_forms: [],
                customers: [],
                currency_type: {},
                documentNewId: null,
                total_global_discount:0,
                loading_search:false,
                taxes:  [],
            }
        },
        async created() {

            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {

                    this.all_customers = response.data.customers
                    // this.customers = response.data.customers
                    this.taxes = response.data.taxes
                    this.currencies = response.data.currencies
                    this.payment_methods = response.data.payment_methods
                    this.payment_forms = response.data.payment_forms
                    this.form.currency_id = (this.currencies.length > 0)?170:null
                    //his.form.payment_form_id = (this.payment_forms.length > 0)?this.payment_forms[0].id:null
                    //(this.payment_methods.length > 0)?this.payment_methods[0].id:null

                    this.form.payment_method_id = 10
                    this.form.payment_form_id = 1

                    this.filterCustomers()

                })

            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })

        },
        methods: {
            ratePrefix(tax = null) {
                if ((tax != null) && (!tax.is_fixed_value)) return null;

                return (this.company.currency != null) ? this.company.currency.symbol : '$';
            },
            clickAddItemInvoice(){
                this.recordItem = null
                this.showDialogAddItem = true
            },
            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
            },
            ediItem(row, index)
            {
                row.indexi = index
                this.recordItem = row
                this.showDialogAddItem = true
            },
            searchRemoteCustomers(input) {

                if (input.length > 0) {

                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/persons-search-customers?${parameters}`)
                            .then(response => {
                                this.customers = response.data.customers
                                this.loading_search = false
                                this.input_person.number = null

                                if(this.customers.length == 0){
                                    this.filterCustomers()
                                    this.input_person.number = input
                                }
                            })
                } else {
                    this.filterCustomers()
                    this.input_person.number = null
                }

            },
            initForm() {

                this.form = {
                    currency_id: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    date_expiration: null,
                    total_discount: 0,
                    total_tax: 0,
                    subtotal: 0,
                    items: [],
                    taxes: [],
                    total: 0,
                    sale: 0,
                    observation: null,
                    time_days_credit: 0,
                    payment_form_id: null,
                    payment_method_id: null,
                    resolution_id: null,
                    prefix: 'RM',
                    number: null,
                }

                this.errors = {}

                this.initInputPerson()

            },
            initInputPerson(){
                this.input_person = {
                    number:null,
                    identity_type_document_id:null
                }
            },
            resetForm() {
                this.initForm()
                this.form.currency_id = (this.currencies.length > 0)?170:null
                this.form.payment_form_id = (this.payment_forms.length > 0)?this.payment_forms[0].id:null;
                this.form.payment_method_id = (this.payment_methods.length > 0)?this.payment_methods[0].id:null;
            },
            changeDateOfIssue() {
            },
            filterCustomers() {
                this.customers = this.all_customers
            },
            addRow(row) {
                if(this.recordItem)
                {
                    this.form.items[this.recordItem.indexi] = row
                    this.recordItem = null
                }
                else{
                    this.form.items.push(JSON.parse(JSON.stringify(row)));
                }
                this.calculateTotal();
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
            },
            calculateTotal() {
                this.setDataTotals()
            },
            setDataTotals() {

                // console.log(val)
                let val = this.form
                val.taxes = JSON.parse(JSON.stringify(this.taxes));

                val.items.forEach(item => {
                    item.tax = this.taxes.find(tax => tax.id == item.tax_id);

                    if (
                        item.discount == null ||
                        item.discount == "" ||
                        item.discount > item.price * item.quantity
                    )
                        this.$set(item, "discount", 0);

                    item.total_tax = 0;

                    if (item.tax != null) {
                        let tax = val.taxes.find(tax => tax.id == item.tax.id);

                        if (item.tax.is_fixed_value)

                            item.total_tax = (
                                item.tax.rate * item.quantity -
                                (item.discount < item.price * item.quantity ? item.discount : 0)
                            ).toFixed(2);

                        if (item.tax.is_percentage)

                            item.total_tax = (
                                (item.price * item.quantity -
                                (item.discount < item.price * item.quantity
                                    ? item.discount
                                    : 0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);

                        if (!tax.hasOwnProperty("total"))
                            tax.total = Number(0).toFixed(2);

                        tax.total = (Number(tax.total) + Number(item.total_tax)).toFixed(2);
                    }

                    item.subtotal = (
                        Number(item.price * item.quantity) + Number(item.total_tax)
                    ).toFixed(2);

                    this.$set(
                        item,
                        "total",
                        (Number(item.subtotal) - Number(item.discount)).toFixed(2)
                    );

                });

                val.subtotal = val.items
                    .reduce(
                        (p, c) => Number(p) + (Number(c.subtotal) - Number(c.discount)),
                        0
                    )
                    .toFixed(2);
                    val.sale = val.items
                    .reduce(
                        (p, c) =>
                        Number(p) + Number(c.price * c.quantity) - Number(c.discount),
                        0
                    )
                    .toFixed(2);
                    val.total_discount = val.items
                    .reduce((p, c) => Number(p) + Number(c.discount), 0)
                    .toFixed(2);
                    val.total_tax = val.items
                    .reduce((p, c) => Number(p) + Number(c.total_tax), 0)
                    .toFixed(2);

                let total = val.items
                    .reduce((p, c) => Number(p) + Number(c.total), 0)
                    .toFixed(2);

                let totalRetentionBase = Number(0);

                // this.taxes.forEach(tax => {
                val.taxes.forEach(tax => {
                    if (tax.is_retention && tax.in_base && tax.apply) {
                        tax.retention = (
                        Number(val.sale) *
                        (tax.rate / tax.conversion)
                        ).toFixed(2);

                        totalRetentionBase =
                        Number(totalRetentionBase) + Number(tax.retention);

                        if (Number(totalRetentionBase) >= Number(val.sale))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                        total -= Number(tax.retention).toFixed(2);
                    }

                    if (
                        tax.is_retention &&
                        !tax.in_base &&
                        tax.in_tax != null &&
                        tax.apply
                    ) {
                        let row = val.taxes.find(row => row.id == tax.in_tax);

                        tax.retention = Number(
                        Number(row.total) * (tax.rate / tax.conversion)
                        ).toFixed(2);

                        if (Number(tax.retention) > Number(row.total))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                        row.retention = Number(tax.retention).toFixed(2);
                        total -= Number(tax.retention).toFixed(2);
                    }
                });

                val.total = Number(total).toFixed(2)

            },
            close() {
                location.href = `/${this.resource}`
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/customer-by-id/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = customer_id
                })
            },
            changeCustomer(){
            },
            async submit() {

                if(!this.form.customer_id){
                    return this.$message.error('Debe seleccionar un cliente')
                }

                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {

                        this.resetForm();
                        this.documentNewId = response.data.data.id;
                        this.showDialogOptions = true;
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {

                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }


                }).then(() => {
                    this.loading_submit = false;
                });
            },

        }
    }
</script>
