<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            Nueva Nota ({{ note.prefix }}-{{ note.number }})
        </div>
        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                        </div>
                        <div class="row mt-4">


                            <div class="col-md-4 col-lg-4 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.type_document_id}">
                                    <label class="control-label">Tipo de nota</label>
                                    <el-select v-model="form.type_document_id" filterable @change="changeDocumentType" popper-class="el-select-document_type" dusk="type_document_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in type_documents" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_document_id" v-text="errors.type_document_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.note_concept_id}">
                                    <label class="control-label">Concepto</label>
                                    <el-select v-model="form.note_concept_id" filterable  popper-class="el-select-document_type" dusk="note_concept_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in note_concepts" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.note_concept_id" v-text="errors.note_concept_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_issue}">
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" :picker-options="datEmision"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_issue" v-text="errors.date_issue[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-2 col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.currency_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_id" disabled @change="changeCurrencyType" filterable>
                                        <el-option v-for="option in currencies" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_id" v-text="errors.currency_id[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-6 col-lg-6 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label">Cliente</label>
                                    <el-select v-model="form.customer_id" disabled filterable @change="changeCustomer" popper-class="el-select-document_type" dusk="customer_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
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
                                                    <small>{{row.tax ? row.tax.name : 'EXCLUIDO'}}</small>
                                                </td>
                                                <td class="text-center">{{row.item.unit_type.name}}</td>
                                                <!-- <td class="text-center">{{(row.item.hasOwnProperty('unit_type') ) ? row.item.unit_type.name : row.item.item.unit_type.name}}</td> -->


                                                <td class="text-right">{{row.quantity}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.quantity"></el-input-number> </td> -->

                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.price)}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.unit_price"></el-input-number> </td> -->


                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.subtotal)}}</td>
                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.discount)}}</td>
                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.total)}}</td>
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
                                    <button type="button" class="ml-3 btn waves-effect waves-light btn-primary" @click.prevent="clickAddRetention">+ Agregar Retención</button>
                                </div>
                            </div>

                            <div class="col-md-12" style="display: flex; flex-direction: column; align-items: flex-end;" v-if="form.items.length > 0">
                                <table>

                                    <tr>
                                        <td>TOTAL VENTA</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ getFormatDecimal(form.sale) }}</td>
                                    </tr>
                                    <tr >
                                        <td>TOTAL DESCUENTO (-)</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ getFormatDecimal(form.total_discount) }}</td>
                                    </tr>
                                    <template v-for="(tax, index) in form.taxes">
                                        <tr v-if="((tax.total > 0) && (!tax.is_retention))" :key="index">
                                            <td >
                                                {{tax.name}}(+)
                                            </td>
                                            <td>:</td>
                                            <td class="text-right">{{ratePrefix()}} {{ getFormatDecimal(Number(tax.total).toFixed(2)) }}</td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td>SUBTOTAL</td>
                                        <td>:</td>
                                        <td class="text-right">{{ratePrefix()}} {{ getFormatDecimal(form.subtotal) }}</td>
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
                                    <h3 class="text-right"><b>TOTAL: </b>{{ratePrefix()}} {{ getFormatDecimal(form.total) }}</h3>
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
                           :operation-type-id="form.operation_type_id"
                           :currency-type-id-active="form.currency_id"
                           :currency-type-symbol-active="ratePrefix()"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           :typeUser="typeUser"
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

        <document-form-retention :showDialog.sync="showDialogAddRetention"
                           @add="addRowRetention"></document-form-retention>


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
    import DocumentFormRetention from './partials/retention.vue'
    import PersonForm from '@views/persons/form.vue'
    // import DocumentOptions from '../documents/partials/options.vue'
    // import {functions, exchangeRate} from '@mixins/functions'
    // import {calculateRowItem} from '../../../helpers/functions'
    // import Helper from "../../../mixins/Helper";
    import DocumentOptions from './partials/options.vue'

    export default {
        props: ['typeUser', 'note'],
        components: {PersonForm, DocumentFormItem, DocumentFormRetention, DocumentOptions},
        // mixins: [Helper],
        data() {
            return {
                datEmision: {
                  disabledDate(time) {
                    return time.getTime() > moment();
                  }
                },
                input_person:{},
                company:{},
                is_client:false,
                recordItem: null,
                resource: 'co-documents',
                showDialogAddItem: false,
                showDialogAddRetention: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                note_concepts: [],
                type_invoices: [],
                currencies: [],
                all_customers: [],
                payment_methods: [],
                payment_forms: [],
                form_payment: {},
                customers: [],
                all_series: [],
                series: [],
                currency_type: {},
                documentNewId: null,
                total_global_discount:0,
                loading_search:false,
                taxes:  [],
                type_documents: [],
                all_type_documents: [],
                noteService: {},
            }
        },
        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    // this.all_customers = response.data.customers;
                    this.taxes = response.data.taxes
                    // console.log(this.taxes)
                    this.all_type_documents = response.data.type_documents;
                    this.currencies = response.data.currencies
                    this.payment_methods = response.data.payment_methods
                    this.payment_forms = response.data.payment_forms

                    // this.filterCustomers();
                    this.typeNoteDocuments()

                })

            await this.initForm()

            await this.getRecordCustomer()

            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })
            this.$eventHub.$on('initInputPerson', () => {
                this.initInputPerson()
            })

        },
        methods: {
            getRecordCustomer(){
                this.$http.get(`/${this.resource}/search/customer/${this.form.customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    // this.form.customer_id = this.document.customer_id
                })
            },
            typeNoteDocuments() {
                this.type_documents = this.all_type_documents.filter(row => row.id != 1);
            },
            ratePrefix(tax = null) {
                if ((tax != null) && (!tax.is_fixed_value)) return null;

                return (this.company.currency != null) ? this.company.currency.symbol : '$';
            },
            keyupCustomer(){

                if(this.input_person.number){

                    if(!isNaN(parseInt(this.input_person.number))){

                        switch (this.input_person.number.length) {
                            case 8:
                                this.input_person.identity_type_document_id = '1'
                                this.showDialogNewPerson = true
                                break;

                            case 11:
                                this.input_person.identity_type_document_id = '6'
                                this.showDialogNewPerson = true
                                break;
                            default:
                                this.input_person.identity_type_document_id = '6'
                                this.showDialogNewPerson = true
                                break;
                        }
                    }
                }
            },
            clickAddItemInvoice(){
                this.recordItem = null
                this.showDialogAddItem = true
            },
            clickAddRetention(){
                this.showDialogAddRetention = true
            },

            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },

            getFormatDecimal(value){
                // Convierte la cadena a un número (si es posible)
                const numericPrice = parseFloat(value);
                if (isNaN(numericPrice)) {
                    // En caso de que la conversión no sea exitosa, maneja el error como desees
                    console.error('No se pudo convertir la cadena a un número.');
                    return value;
                }

                // Asumiendo que numericPrice es un número
                const formattedPrice = numericPrice.toLocaleString('en-US', {
                    style: 'decimal',  // Estilo 'decimal' para separadores de mil y dos decimales
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                return formattedPrice;
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
                    let parameters = `input=${input}&type_document_id=${this.form.type_document_id}&operation_type_id=${this.form.operation_type_id}`

                    this.$http.get(`/${this.resource}/search/customers?${parameters}`)
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
//                console.log(this.note)
                this.form = {
                    customer_id: this.note.customer_id,
                    type_document_id: null,
                    note_concept_id: null,
                    currency_id: this.note.currency_id,
                    date_issue: moment().format('YYYY-MM-DD'),
                    date_expiration: null,
                    type_invoice_id: null,
                    total_discount: 0,
                    total_tax: 0,
                    watch: false,
                    subtotal: 0,
                    items: [],
                    taxes: [],
                    total: 0,
                    sale: 0,
                    observation: null,
                    time_days_credit: 0,
                    id: this.note.id,
                    reference_id: this.note.id,
                    payment_form_id: this.note.payment_form_id,
                    payment_method_id: this.note.payment_method_id,
                    correlative_api: this.note.correlative_api,
                    response_api_cufe:  this.note.response_api_cufe,
                    note_service: {}
                }

                this.noteService.customer = {
                    identification_number: this.note.customer.number,
                    name: this.note.customer.name,
                    phone: this.note.customer.telephone,
                    address: this.note.customer.address,
                    email: this.note.customer.email,
                    merchant_registration: "0000-00",
                    type_document_identification_id: this.note.customer.identity_document_type_id,
                    type_organization_id: this.note.customer.type_person_id,
                    municipality_id_fact: this.note.customer.city_id,
                    type_regime_id: this.note.customer.type_regime_id
                }

                if (this.note.customer.type_person_id == 1) {
                    this.noteService.customer.dv = this.note.customer.dv;
                }
                this.errors = {}
                this.$eventHub.$emit('eventInitForm')

                this.initInputPerson()

            },
            initInputPerson(){
                this.input_person = {
                    number:null,
                    identity_type_document_id:null
                }
            },
            resetForm() {
                this.activePanel = 0
                this.initForm()
            },
            async changeOperationType() {
                await this.filterCustomers();
                await this.setDataDetraction();
            },
            changeEstablishment() {
                this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
                this.filterSeries()
            },
            changeDocumentType() {
                this.conceptss()
            },
            conceptss() {
                this.form.note_concept_id = null;

                if (this.form.type_document_id != null)
                    this.getConcepts(this.form.type_document_id).then(
                        rows => (this.note_concepts = rows)
                    );
            },
            getConcepts(val) {

                return axios.post(`/concepts/${val}`).then(response => {
                                return response.data;
                            })
                            .catch(error => {
                                console.log(error)
                            });

            },
            cleanCustomer(){
                this.form.customer_id = null
                // this.customers = []
            },
            changeDateOfIssue() {
                this.form.date_expiration = this.form.date_of_issue
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            assignmentDateOfPayment(){
                this.form.payments.forEach((payment)=>{
                    payment.date_of_payment = this.form.date_of_issue
                })
            },

            filterSeries() {
                this.form.series_id = null
                this.series = _.filter(this.all_series, {'establishment_id': this.form.establishment_id,
                                                         'type_document_id': this.form.type_document_id,
                                                         'contingency': this.is_contingency});
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null
            },
            filterCustomers() {
                this.customers = this.all_customers
            },
            addRow(row) {
                if(this.recordItem)
                {
                    //this.form.items.$set(this.recordItem.indexi, row)
                    this.form.items[this.recordItem.indexi] = row
                    this.recordItem = null
                }
                else{
                    this.form.items.push(JSON.parse(JSON.stringify(row)));
                }
                // console.log(this.form)
                this.calculateTotal();
            },
            async addRowRetention(row){

                await this.taxes.forEach(tax => {
                    if(tax.id == row.tax_id){
                        tax.apply = true
                    }
                });

                await this.calculateTotal()

            },
            cleanTaxesRetention(tax_id){

                this.taxes.forEach(tax => {
                    if(tax.id == tax_id){
                        tax.apply = false
                        tax.retention = 0
                    }
                })

            },
            async clickRemoveRetention(index){
                // console.log(index, "w")
                this.form.taxes[index].apply = false
                this.form.taxes[index].retention = 0
                await this.cleanTaxesRetention(this.form.taxes[index].id)
                await this.calculateTotal()

            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
                // this.currency_type = _.find(this.currencies, {'id': this.form.currency_id})
                // let items = []
                // this.form.items.forEach((row) => {
                //     items.push(calculateRowItem(row, this.form.currency_id, this.form.exchange_rate_sale))
                // });
                // this.form.items = items
                // this.calculateTotal()
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
                location.href = (this.is_contingency) ? `/contingencies` : `/${this.resource}`
            },
            reloadDataCustomers(customer_id) {
                // this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                //     this.customers = response.data
                //     this.form.customer_id = customer_id
                // })
                this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = customer_id
                })
            },
            changeCustomer() {
            },
            async submit() {

                if(!this.form.customer_id){
                    return this.$message.error('Debe seleccionar un cliente')
                }

                if(!this.form.note_concept_id){
                    return this.$message.error('Debe seleccionar un concepto')
                }

                await this.generateNoteService();
                this.form.note_service = this.noteService;
                // return

                this.loading_submit = true
                this.$http.post(`/${this.resource}/note`, this.form).then(response => {
                    if (response.data.success) {
                        this.resetForm();
                        this.documentNewId = response.data.data.id;
                        // this.$message.success(response.data.message);
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

            getTypeDocumentService()
            {
                let id = this.form.type_document_id
                let id_service = 0

                if(id == 2)
                {
                    id_service = 5
                }
                else if(id == 3)
                {
                    id_service = 4
                }

                return id_service
            },
            async generateNoteService() {

                // let contex = this
                this.noteService.number = 0;
                this.noteService.type_document_id = await this.getTypeDocumentService(),
                this.noteService.date = "";
                this.noteService.time = "";

                this.noteService.billing_reference = {
                    number: String(this.note.correlative_api),
                    uuid: this.note.response_api_cufe,
                    issue_date: moment(this.note.date_issue).format('YYYY-MM-DD')
                };

                this.noteService.tax_totals = await this.getTaxTotal();
                this.noteService.with_holding_tax_total = await this.getWithHolding();

                if(this.noteService.type_document_id == 4)
                {
                    this.noteService.legal_monetary_totals = await this.getLegacyMonetaryTotal();
                    this.noteService.credit_note_lines = await this.getCreditNoteLines();
                    this.noteService.allowance_charges = await this.createAllowanceCharge(
                        this.noteService.legal_monetary_totals.allowance_total_amount, this.noteService.legal_monetary_totals.line_extension_amount
                    );
                }
                else if(this.noteService.type_document_id == 5)
                {
                    this.noteService.requested_monetary_totals = await this.getLegacyMonetaryTotal();
                    this.noteService.debit_note_lines = await this.getCreditNoteLines();

                    /*this.noteService.allowance_charges = await this.createAllowanceCharge(
                        this.noteService.requested_monetary_totals.allowance_total_amount, this.noteService.requested_monetary_totals.line_extension_amount
                    );*/
                }

            },

            // getCustomer() {

            //     let customer = this.customers.find(x => x.id == this.form.customer_id);
            //     let obj = {
            //         identification_number: customer.identification_number,
            //         name: customer.name,
            //         phone: customer.phone,
            //         address: customer.address,
            //         email: customer.email,
            //         merchant_registration: "000000"
            //     };

            //     this.form.customer_id = customer.id

            //     if (customer.type_person_id == 2) {
            //         obj.dv = customer.dv;
            //     }

            //     return obj;
            // },

            getTaxTotal() {
                let tax = [];
                this.form.items.forEach(element => {
                    let find = tax.find(x => element.tax !== undefined && x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
                    if(find)
                    {
                        let indexobj = tax.findIndex(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
                        tax.splice(indexobj, 1);
                        tax.push({
                            tax_id: find.tax_id,
                            tax_amount: this.cadenaDecimales(Number(find.tax_amount) + Number(element.total_tax)),
                            percent: this.cadenaDecimales(find.percent),
                            taxable_amount: this.cadenaDecimales(Number(find.taxable_amount) + Number(element.price) * Number(element.quantity)) - Number(element.discount)
                        });
                    }
                    else {
                        if(element.tax !== undefined){
                            tax.push({
                                tax_id: element.tax.type_tax_id,
                                tax_amount: this.cadenaDecimales(Number(element.total_tax)),
                                percent: this.cadenaDecimales(Number(element.tax.rate)),
                                taxable_amount: this.cadenaDecimales((Number(element.price) * Number(element.quantity)) - Number(element.discount))
                            });
                        }
                    }
                });
                this.tax_amount_calculate = tax;
                return tax;
            },

            getLegacyMonetaryTotal() {
                let line_ext_am = 0;
                let tax_incl_am = 0;
                let allowance_total_amount = 0;
                this.form.items.forEach(element => {
                    line_ext_am += (Number(element.price) * Number(element.quantity)) - Number(element.discount) ;
//                    allowance_total_amount += Number(element.discount);
                });

                let total_tax_amount = 0;
                this.tax_amount_calculate.forEach(element => {
                    total_tax_amount += Number(element.tax_amount);
                });

                let tax_excl_am = 0;
                this.tax_amount_calculate.forEach(element => {
                    tax_excl_am += Number(element.taxable_amount);
                });
                tax_incl_am = line_ext_am + total_tax_amount;

                return {
                    line_extension_amount: this.cadenaDecimales(line_ext_am),
                    tax_exclusive_amount: this.cadenaDecimales(tax_excl_am),
                    tax_inclusive_amount: this.cadenaDecimales(tax_incl_am),
                    allowance_total_amount: this.cadenaDecimales(allowance_total_amount),
                    charge_total_amount: "0.00",
                    payable_amount: this.cadenaDecimales(tax_incl_am)
//                    payable_amount: this.cadenaDecimales(tax_incl_am - allowance_total_amount)
                };
            },

            createAllowanceCharge(amount, base) {
                return [
                    {
                        discount_id: 1,
                        charge_indicator: false,
                        allowance_charge_reason: "DESCUENTO GENERAL",
                        amount: this.cadenaDecimales(amount),
                        base_amount: this.cadenaDecimales(base)
                    }
                ]
            },

            getCreditNoteLines() {
                let data = this.form.items.map(x => {
                    if(x.tax !== undefined){
                        return {
                            unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                            invoiced_quantity: x.quantity,
                            line_extension_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                            free_of_charge_indicator: false,
                            allowance_charges: [
                                {
                                    charge_indicator: false,
                                    allowance_charge_reason: "DESCUENTO GENERAL",
                                    amount: this.cadenaDecimales(x.discount),
                                    base_amount: this.cadenaDecimales(Number(x.price) * Number(x.quantity))
                                }
                            ],
                            tax_totals: [
                                {
                                    tax_id: x.tax.type_tax_id,
                                    tax_amount: this.cadenaDecimales(x.total_tax),
                                    taxable_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                                    percent: this.cadenaDecimales(x.tax.rate)
                                }
                            ],
                            description: x.item.name,
                            code: x.item.internal_id,
                            type_item_identification_id: 4,
                            price_amount: this.cadenaDecimales(Number(x.price) + (Number(x.total_tax) / Number(x.quantity))),
                            base_quantity: x.quantity
                        };
                    }
                    else
                    {
                        return {
                            unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                            invoiced_quantity: x.quantity,
                            line_extension_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                            free_of_charge_indicator: false,
                            allowance_charges: [
                                {
                                    charge_indicator: false,
                                    allowance_charge_reason: "DESCUENTO GENERAL",
                                    amount: this.cadenaDecimales(x.discount),
                                    base_amount: this.cadenaDecimales(Number(x.price) * Number(x.quantity))
                                }
                            ],
                            description: x.item.name,
                            code: x.item.internal_id,
                            type_item_identification_id: 4,
                            price_amount: this.cadenaDecimales(Number(x.price) + (Number(x.total_tax) / Number(x.quantity))),
                            base_quantity: x.quantity
                        };
                    }
                });
                return data;
            },

            getWithHolding() {

                let total = this.form.total
                let list = this.form.taxes.filter(function(x) {
                    return x.is_retention && x.apply;
                });

                return list.map(x => {
                    return {
                        tax_id: x.type_tax_id,
                        tax_amount: this.cadenaDecimales(x.retention),
                        percent: this.cadenaDecimales(x.rate),
                        taxable_amount: this.cadenaDecimales(total),
                    };
                });

            },

            cadenaDecimales(amount){
                if(amount.toString().indexOf(".") != -1)
                    return amount.toString();
                else
                    return amount.toString()+".00";
                },
            },
    }
</script>
