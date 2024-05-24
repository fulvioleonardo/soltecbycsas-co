<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <!-- <div class="card-header bg-info">
            <h3 class="my-0">Nuevo Comprobante</h3>
        </div> -->
        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-2 text-center mt-3 mb-0">
                            <logo url="/" :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''" ></logo>
                        </div>
                        <div class="col-sm-6 text-left mt-3 mb-0">
                            <address class="ib mr-2" >
                                <span class="font-weight-bold d-block">COTIZACIÓN</span>
                                <span class="font-weight-bold d-block">COT-XXX</span>
                                <span class="font-weight-bold">{{company.name}}</span>
                                <br>
                                <div v-if="establishment.address != '-'">{{ establishment.address }}, </div> {{ establishment.city.name }}, {{ establishment.department.name }} - {{ establishment.country.name }}
                                <br>
                                {{establishment.email}} - <span v-if="establishment.telephone != '-'">{{establishment.telephone}}</span>
                            </address>
                        </div>
                        <div class="col-sm-4">

                            <!-- <el-checkbox class="mt-3" v-model="form.active_terms_condition" @change="changeTermsCondition">Términos y condiciones del contrato</el-checkbox> -->

                        </div>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row mt-1">
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
                                        :loading="loading_search">

                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <!--<label class="control-label">Fecha de emisión</label>-->
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                    <label class="control-label">Tiempo de Validez</label>
                                    <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="true"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.delivery_date}">
                                    <label class="control-label">Tiempo de Entrega</label>
                                    <el-date-picker v-model="form.delivery_date" type="date" value-format="yyyy-MM-dd" :clearable="true"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.delivery_date" v-text="errors.delivery_date[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" >
                                    <label class="control-label">Dirección de envío
                                    </label>
                                    <el-input v-model="form.shipping_address"></el-input>
                                    <small class="form-control-feedback" v-if="errors.shipping_address" v-text="errors.shipping_address[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.payment_method_type_id}">
                                    <label class="control-label">
                                        Término de pago
                                    </label>
                                    <el-select v-model="form.payment_method_type_id" filterable @change="changePaymentMethodType">
                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payment_method_type_id" v-text="errors.payment_method_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" >
                                    <label class="control-label">Número de cuenta
                                    </label>
                                    <el-input v-model="form.account_number"></el-input>
                                    <small class="form-control-feedback" v-if="errors.account_number" v-text="errors.account_number[0]"></small>
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

                            <!-- <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label">Tipo de cambio
                                        <el-tooltip class="item" effect="dark" content="Tipo de cambio del día, extraído de SUNAT" placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div> -->

                            <div class="col-lg-8 mt-2" >

                                <table>
                                    <thead>
                                        <tr width="100%">
                                            <th v-if="form.payments.length>0" class="pb-2">Método de pago</th>
                                            <th v-if="form.payments.length>0" class="pb-2">Destino</th>
                                            <th v-if="form.payments.length>0" class="pb-2">Referencia</th>
                                            <th v-if="form.payments.length>0" class="pb-2">Monto</th>
                                            <th width="15%"><a href="#" @click.prevent="clickAddPayment" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in form.payments" :key="index">
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_method_type_id" >
                                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_destination_id" filterable >
                                                        <el-option v-for="option in payment_destinations" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2"  >
                                                    <el-input v-model="row.reference"></el-input>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2" >
                                                    <el-input v-model="row.payment"></el-input>
                                                </div>
                                            </td>
                                            <td class="series-table-actions text-center">
                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <br>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label">Descripcion
                                    </label>
                                    <el-input  type="textarea"  :rows="3" v-model="form.description"></el-input>
                                    <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
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
                                                    <template v-if="row.item.presentation">
                                                        {{row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''}}
                                                    </template>
                                                    <br/>
                                                    <small>{{row.tax.name}}</small>
                                                </td>
                                                <td class="text-center">{{ row.item.unit_type.name }}</td>
                                                <td class="text-right">{{row.quantity}}</td>
                                                <td class="text-right">{{ ratePrefix() }} {{ getFormatUnitPriceRow(row.unit_price) }}</td>

                                                <td class="text-right">{{ ratePrefix() }} {{ row.subtotal }}</td>
                                                <td class="text-right">{{ ratePrefix() }} {{ row.discount }}</td>
                                                <td class="text-right">{{ratePrefix()}} {{row.total}}</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                </td>
                                            </tr>
                                            <tr><td colspan="9"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="showDialogAddItem = true">+ Agregar Producto</button>
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

                            </div>
                            <div class="col-md-8 mt-3">

                            </div>

                            <div class="col-md-4">
                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ ratePrefix() }} {{ form.total }}</h3>
                            </div>

                        </div>

                    </div>


                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                    </div>
                </form>
            </div>
        </div>

        <quotation-form-item :showDialog.sync="showDialogAddItem"
                           @add="addRow"></quotation-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"></person-form>

        <quotation-options :showDialog.sync="showDialogOptions"
                          :recordId="quotationNewId"
                          :typeUser="typeUser"
                          :showGenerate="false"
                          :showClose="false"></quotation-options>

        <terms-condition :showDialog.sync="showDialogTermsCondition"
                          :form="form"
                          :showClose="false"></terms-condition>
    </div>
</template>

<script>
    import TermsCondition from './partials/terms_condition.vue'
    import QuotationFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import QuotationOptions from '../quotations/partials/options.vue'
    import Logo from '../companies/logo.vue'

    export default {
        props:['typeUser', 'saleOpportunityId'],
        components: {QuotationFormItem, PersonForm, QuotationOptions, Logo, TermsCondition},
        data() {
            return {
                resource: 'quotations',
                showDialogTermsCondition: false,
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_customers: [],
                payment_method_types: [],
                customers: [],
                company: {},
                taxes:  [],
                establishments: [],
                establishment: null,
                currency_type: {},
                quotationNewId: null,
                payment_destinations:  [],
                activePanel: 0,
                currencies:  [],
                loading_search:false
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.taxes = response.data.taxes
                    this.currencies = response.data.currencies
                    this.establishments = response.data.establishments
                    this.all_customers = response.data.customers
                    this.company = response.data.company

                    let find_currency = _.find(this.currencies, {id:170})
                    this.form.currency_id = find_currency ? find_currency.id: null
                    this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                    this.payment_method_types = response.data.payment_method_types
                    this.payment_destinations = response.data.payment_destinations

                    this.changeEstablishment()
                    this.changeDateOfIssue()
                    this.changeCurrencyType()
                    this.allCustomers()
                })
            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })

            await this.createQuotationFromSO()
        },
        methods: {
            changeTermsCondition(){

                if(this.form.active_terms_condition){

                    this.showDialogTermsCondition = true

                }else{
                    this.form.terms_condition = null
                }
            },
            clickAddPayment() {
                this.form.payments.push({
                    id: null,
                    document_id: null,
                    date_of_payment:  moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    payment_destination_id:'cash',
                    payment: 0,

                });
            },
            clickCancel(index) {
                this.form.payments.splice(index, 1);
            },
            async createQuotationFromSO(){

                if(this.saleOpportunityId) {

                    let sale_opportunity = {}

                    await this.$http.get(`/sale-opportunities/record/${this.saleOpportunityId}`)
                        .then(response => {

                            sale_opportunity = response.data.data.sale_opportunity;
                            this.reloadDataCustomers(sale_opportunity.customer_id)

                        })

                    await this.assignDataSaleOpportunity(sale_opportunity)
                }

            },
            assignDataSaleOpportunity(sale_opportunity){

                this.form.establishment_id = sale_opportunity.establishment_id
                this.form.time_of_issue = moment().format("HH:mm:ss")
                this.form.customer_id = sale_opportunity.customer_id
                this.form.currency_type_id = sale_opportunity.currency_type_id
                this.form.total_exportation = sale_opportunity.total_exportation
                this.form.total_free = sale_opportunity.total_free
                this.form.total_taxed = sale_opportunity.total_taxed
                this.form.total_unaffected = sale_opportunity.total_unaffected
                this.form.total_exonerated = sale_opportunity.total_exonerated
                this.form.total_igv = sale_opportunity.total_igv
                this.form.total_taxes = sale_opportunity.total_taxes
                this.form.total_value = sale_opportunity.total_value
                this.form.total = sale_opportunity.total
                this.form.items = sale_opportunity.items
                this.form.sale_opportunity_id = sale_opportunity.id;

            },
            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },
            async changePaymentMethodType(flag_submit = true){
                let payment_method_type = await _.find(this.payment_method_types, {'id':this.form.payment_method_type_id})
                if(payment_method_type){

                    if(payment_method_type.number_days){
                        this.form.date_of_issue =  moment().add(payment_method_type.number_days,'days').format('YYYY-MM-DD');
                        this.changeDateOfIssue()
                    }
                    // else{
                    //     if(flag_submit){
                    //         this.form.date_of_issue = moment().format('YYYY-MM-DD')
                    //         this.changeDateOfIssue()
                    //     }
                    // }
                }
            },
            searchRemoteCustomers(input) {

                if (input.length > 0) {
                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                            .then(response => {
                                this.customers = response.data.customers
                                this.loading_search = false
                                if(this.customers.length == 0){this.allCustomers()}
                            })
                } else {
                    this.allCustomers()
                }

            },
            initForm() {
                this.errors = {}
                this.form = {
                    description: '',
                    prefix:'COT',
                    establishment_id: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total: 0,
                    date_of_due: null,
                    delivery_date: null,
                    items: [],
                    payment_method_type_id:'10',
                    additional_information:null,
                    shipping_address:null,
                    account_number:null,
                    terms_condition:null,
                    active_terms_condition:false,
                    actions: {
                        format_pdf:'a4',
                    },
                    payments: [],
                    sale_opportunity_id:null,

                    sale_opportunity_id:null,

                    sale: 0,
                    taxes: [],
                    total_tax: 0,
                    total_discount: 0,
                    subtotal: 0,
                }

                this.clickAddPayment()

            },
            resetForm() {
                this.activePanel = 0
                this.initForm()

                let find_currency = _.find(this.currencies, {id:170})
                this.form.currency_id = find_currency ? find_currency.id: null
                this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.changeEstablishment()
                this.changeDateOfIssue()
                this.changeCurrencyType()
                this.allCustomers()
            },
            changeEstablishment() {
                this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})

            },
            cleanCustomer(){
                this.form.customer_id = null;
            },
            changeDateOfIssue() {
                // this.form.date_of_due = this.form.date_of_issue > this.form.date_of_due ? this.form.date_of_issue:null
            },
            allCustomers() {
                this.customers = this.all_customers
            },
            addRow(row) {
                this.form.items.push(JSON.parse(JSON.stringify(row)));

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
                        item.discount > item.unit_price * item.quantity
                    )
                        this.$set(item, "discount", 0);

                    item.total_tax = 0;

                    if (item.tax != null) {
                        let tax = val.taxes.find(tax => tax.id == item.tax.id);

                        if (item.tax.is_fixed_value)

                            item.total_tax = (
                                item.tax.rate * item.quantity -
                                (item.discount < item.unit_price * item.quantity ? item.discount : 0)
                            ).toFixed(2);

                        if (item.tax.is_percentage)

                            item.total_tax = (
                                (item.unit_price * item.quantity -
                                (item.discount < item.unit_price * item.quantity
                                    ? item.discount
                                    : 0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);

                        if (!tax.hasOwnProperty("total"))
                            tax.total = Number(0).toFixed(2);

                        tax.total = (Number(tax.total) + Number(item.total_tax)).toFixed(2);
                    }

                    item.subtotal = (
                        Number(item.unit_price * item.quantity) + Number(item.total_tax)
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
                        Number(p) + Number(c.unit_price * c.quantity) - Number(c.discount),
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
            ratePrefix(tax = null) {
                if ((tax != null) && (!tax.is_fixed_value)) return null;

                return (this.company.currency != null) ? this.company.currency.symbol : '$';
            },
            validate_payments(){

                //eliminando items de pagos
                for (let index = 0; index < this.form.payments.length; index++) {
                    if(parseFloat(this.form.payments[index].payment) === 0)
                        this.form.payments.splice(index, 1)
                }

                let error_by_item = 0
                let acum_total = 0

                this.form.payments.forEach((item)=>{
                    acum_total += parseFloat(item.payment)
                    if(item.payment <= 0 || item.payment == null) error_by_item++;
                })

                return  {
                    error_by_item : error_by_item,
                    acum_total : acum_total
                }

            },
            async submit() {

                let validate = await this.validate_payments()
                if(validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
                    return this.$message.error('Los montos ingresados superan al monto a pagar o son incorrectos');
                }

                if(this.form.date_of_issue > this.form.date_of_due)
                    return this.$message.error('La fecha de emisión no puede ser posterior a la de vencimiento');

                if(this.form.date_of_issue > this.form.delivery_date)
                    return this.$message.error('La fecha de emisión no puede ser posterior a la de entrega');

                this.loading_submit = true
                // await this.changePaymentMethodType(false)
                await this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.resetForm();
                        this.quotationNewId = response.data.data.id;

                        if(this.saleOpportunityId){
                            this.$message.success(`La cotización ${response.data.data.number_full} fue generada`)
                            this.close()
                        }else{
                            this.showDialogOptions = true;
                        }
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
            close() {
                location.href = '/quotations'
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = customer_id
                })
            },
        }
    }
</script>