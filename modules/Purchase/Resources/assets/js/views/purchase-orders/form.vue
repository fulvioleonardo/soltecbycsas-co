<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <!-- <div class="card-header bg-info">
            <h3 class="my-0">Nueva Compra</h3>
        </div> -->
        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-2 text-center mt-3 mb-0">
                            <logo url="/" :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''" ></logo>
                        </div>
                        <div class="col-sm-10 text-left mt-3 mb-0">
                            <address class="ib mr-2" >
                                <span class="font-weight-bold d-block">ORDEN DE COMPRA</span>
                                <span class="font-weight-bold d-block">OC-XXX</span>
                                <span class="font-weight-bold">{{company.name}}</span>
                                <br>
                                <div v-if="establishment.address != '-'">{{ establishment.address }}, </div> {{ establishment.city.name }}, {{ establishment.department.name }} - {{ establishment.country.name }}
                                <br>
                                {{establishment.email}} - <span v-if="establishment.telephone != '-'">{{establishment.telephone}}</span>
                            </address>
                        </div>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group" :class="{'has-danger': errors.supplier_id}">
                                    <label class="control-label">
                                        Proveedor
                                        <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                    </label>
                                    <el-select v-model="form.supplier_id" filterable @change="changeSupplier" ref="select_person" @keyup.native="keyupSupplier" @keyup.enter.native="keyupEnterSupplier">
                                        <el-option v-for="option in suppliers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.supplier_id" v-text="errors.supplier_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fec Emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                    <label class="control-label">Fec. Vencimiento</label>
                                    <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
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
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.payment_method_type_id}">
                                    <label class="control-label">
                                        Forma de pago
                                    </label>
                                    <el-select v-model="form.payment_method_type_id" filterable @change="changePaymentMethodType">
                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payment_method_type_id" v-text="errors.payment_method_type_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-3" style="margin-top:29px;">
                                <div class="form-group" :class="{'has-danger': errors.file}">
                                    <el-upload
                                            :data="{'type': 'purchase-order-attached'}"
                                            :headers="headers"
                                            :multiple="false"
                                            :on-remove="handleRemove"
                                            :action="`/${resource}/upload`"
                                            :show-file-list="true"
                                            :file-list="fileList"
                                            :on-success="onSuccess"
                                            :limit="1"
                                            >
                                        <el-button slot="trigger" type="primary">Seleccione un archivo (PDF/JPG)</el-button>
                                    </el-upload>
                                    <small class="form-control-feedback" v-if="errors.file" v-text="errors.file[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="showDialogAddItem = true">+ Agregar Producto</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" v-if="form.items.length > 0">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descripción</th>
                                            <!-- <th>Almacén</th> -->
                                            <th class="text-center">Unidad</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">Precio Unitario</th>
                                            <th class="text-right">Descuento</th>
                                            <th class="text-right">Total</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.items" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.item.name }}<br/>
                                                <small>{{row.tax.name}}</small>
                                            </td>
                                            <!-- <td class="text-left">{{ row.warehouse_description }}</td> -->
                                            <td class="text-center">{{ row.item.unit_type.name }}</td>
                                            <td class="text-right">{{ row.quantity }}</td>
                                            <!-- <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td> -->
                                            <td class="text-right">{{ ratePrefix() }} {{ getFormatUnitPriceRow(row.unit_price) }}</td>
                                            <td class="text-right">{{ ratePrefix() }} {{ row.discount }}</td>
                                            <td class="text-right">{{ ratePrefix() }} {{ row.total }}</td>
                                            <td class="text-right">
                                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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

                            <div class="col-md-12">
                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL COMPRAS: </b>{{ currency_type.symbol }} {{ form.total }}</h3>

                                <template v-if="is_perception_agent">
                                    <hr>
                                    <div class="row mt-1">
                                        <div class="col-lg-10 float-right">
                                            <label class="float-right control-label">NÚMERO PERCEPCIÓN: </label>
                                        </div>
                                        <div class="col-lg-2 float-right">
                                            <div class="form-group" :class="{'has-danger': errors.perception_number}">
                                                <el-input v-model="form.perception_number"></el-input>

                                                <small class="form-control-feedback" v-if="errors.perception_number" v-text="errors.perception_number[0]"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-10 float-right">
                                            <label class="float-right control-label">FEC EMISIÓN PERCEPCIÓN: </label>
                                        </div>
                                        <div class="col-lg-2 float-right">
                                            <div class="form-group" :class="{'has-danger': errors.perception_date}">
                                                <el-date-picker v-model="form.perception_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                                <small class="form-control-feedback" v-if="errors.perception_date" v-text="errors.perception_date[0]"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-10 float-right">
                                            <label class="float-right control-label">IMPORTE PERCEPCIÓN: </label>
                                        </div>
                                        <div class="col-lg-2 float-right">
                                            <div class="form-group" :class="{'has-danger': errors.total_perception}">
                                                <el-input v-model="form.total_perception" @input="inputTotalPerception" :readonly="true"></el-input>

                                                <small class="form-control-feedback" v-if="errors.total_perception" v-text="errors.total_perception[0]"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="text-right" v-if="form.total > 0 && !hide_button"><b>MONTO TOTAL : </b>{{ ratePrefix() }} {{ total_amount }}</h3>

                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0 && !hide_button">Generar</el-button>
                    </div>
                </form>
            </div>
        </div>

        <purchase-form-item :showDialog.sync="showDialogAddItem"
                           :currency-type-id-active="form.currency_type_id"
                           @add="addRow"></purchase-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="suppliers"
                        :input_person="input_person"
                       :external="true"></person-form>

        <purchase-options :showDialog.sync="showDialogOptions"
                          :recordId="purchaseNewId"
                          :isUpdate="propIsUpdate"
                          :showClose="false"></purchase-options>
    </div>
</template>

<script>

    import PurchaseFormItem from './partials/item.vue'
    import PurchaseOptions from './partials/options.vue'
    import Logo from '@views/companies/logo.vue'
    import PersonForm from '@views/persons/form.vue'

    export default {
        props: ['id', 'saleOpportunity'],
        components: {PurchaseFormItem, PersonForm, PurchaseOptions, Logo},
        data() {
            return {
                currencies: [],
                input_person:{},
                resource: 'purchase-orders',
                showDialogAddItem: false,
                headers: headers_token,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                hide_button: false,
                is_perception_agent: false,
                errors: {},
                loading_form: false,
                form: {},
                aux_supplier_id:null,
                total_amount:0,
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                payment_method_types: [],
                all_suppliers: [],
                suppliers: [],
                company: {},
                operation_types: [],
                establishment: {},
                all_series: [],
                series: [],
                propIsUpdate:false,
                fileList: [],
                currency_type: {},
                taxes:  [],
                purchaseNewId: null
            }
        },
        async created() {
            // console.log(this.id, this.saleOpportunity)
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {

                    this.currencies = response.data.currencies
                    this.taxes = response.data.taxes
                    this.establishment = response.data.establishment
                    this.suppliers = response.data.suppliers
                    this.payment_method_types = response.data.payment_method_types
                    this.company = response.data.company


                    let find_currency = _.find(this.currencies, {id:170})
                    this.form.currency_id = find_currency ? find_currency.id: null
                    this.form.establishment_id = (this.establishment.id) ? this.establishment.id:null

                    this.changeDateOfIssue()
                    // this.changeDocumentType()
                    this.changeCurrencyType()
                })

            this.$eventHub.$on('reloadDataPersons', (supplier_id) => {
                this.reloadDataSuppliers(supplier_id)
           })
            this.loading_form = true

            this.$eventHub.$on('initInputPerson', () => {
                this.initInputPerson()
            })

            await this.isUpdate()
            await this.generateFromSaleOpportunity()

        },
        methods: {
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

            generateFromSaleOpportunity(){

                if(this.saleOpportunity){

                    this.form.currency_type_id = this.saleOpportunity.currency_type_id
                    this.form.items = this.saleOpportunity.items
                    this.form.total_exportation = this.saleOpportunity.total_exportation
                    this.form.total_free = this.saleOpportunity.total_free
                    this.form.total_taxed = this.saleOpportunity.total_taxed
                    this.form.total_unaffected = this.saleOpportunity.total_unaffected
                    this.form.total_exonerated = this.saleOpportunity.total_exonerated
                    this.form.total_igv = this.saleOpportunity.total_igv
                    this.form.total_taxes = this.saleOpportunity.total_taxes
                    this.form.total_value = this.saleOpportunity.total_value
                    this.form.total = this.saleOpportunity.total
                    this.form.sale_opportunity_id = this.saleOpportunity.id
                    // console.log(this.form)
                }

            },
            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },
            onSuccess(response, file, fileList) {
                // console.log(response, file, fileList)
                this.fileList = fileList
                if (response.success) {
                    this.form.attached = response.data.filename
                    this.form.image_url = response.data.temp_image
                    this.form.attached_temp_path = response.data.temp_path
                } else {
                    this.$message.error(response.message)
                }
            },
            handleRemove(file, fileList) {
                this.form.upload_filename = null
                this.form.temp_path = null
                this.fileList = []
            },
            async isUpdate(){

                if (this.id) {
                    // console.log(this.id);
                    await this.$http.get(`/${this.resource}/record/${this.id}`)
                        .then(response => {
                            // console.log(response)
                            this.form = response.data.data.purchase_order;
                            if(this.form.upload_filename){
                                this.fileList.push({
                                    name:this.form.upload_filename,
                                    url:this.form.upload_filename,
                                })
                            }

                            // this.form.suppliers = Object.values(response.data.data.purchase_quotation.suppliers);
                        })

                    this.button_text = 'Actualizar'
                    this.propIsUpdate = true
                }else{
                    this.propIsUpdate = false
                }

            },
            initInputPerson(){
                this.input_person = {
                    number:'',
                    identity_document_type_id:''
                }
            },
            keyupEnterSupplier(){

                if(this.input_person.number){

                    if(!isNaN(parseInt(this.input_person.number))){

                        switch (this.input_person.number.length) {
                            case 8:
                                this.input_person.identity_document_type_id = '1'
                                this.showDialogNewPerson = true
                                break;

                            case 11:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                            default:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                        }
                    }
                }
            },
            keyupSupplier(e){

                if(e.key !== "Enter"){

                    this.input_person.number = this.$refs.select_person.$el.getElementsByTagName('input')[0].value
                    let exist_persons = this.suppliers.filter((supplier)=>{
                        let pos = supplier.description.search(this.input_person.number);
                        return (pos >- 1)
                    })

                    this.input_person.number = (exist_persons.length == 0) ? this.input_person.number : null
                }

            },
            inputSeries(){

                const pattern = new RegExp('^[A-Z0-9]+$', 'i');
                if(!pattern.test(this.form.series)){
                    this.form.series = this.form.series.substring(0, this.form.series.length - 1);
                } else {
                    this.form.series = this.form.series.toUpperCase()
                }

            },
            changePaymentMethodType(flag_submit = true){
                let payment_method_type = _.find(this.payment_method_types, {'id':this.form.payment_method_type_id})
                if(payment_method_type.number_days){
                    this.form.date_of_issue =  moment().add(payment_method_type.number_days,'days').format('YYYY-MM-DD');
                    this.changeDateOfIssue()
                }else{
                    if(flag_submit){
                        this.form.date_of_issue = moment().format('YYYY-MM-DD')
                        this.changeDateOfIssue()
                    }
                }
            },
            inputTotalPerception(){
                this.total_amount = parseFloat(this.form.total) + parseFloat(this.form.total_perception)
                if(isNaN(this.total_amount)){
                    this.hide_button = true
                }else{
                    this.hide_button = false

                }
            },
            changeSupplier(){
                this.calculatePerception()
            },
            filterSuppliers() {

                // if(this.form.document_type_id === '01') {
                //     this.suppliers = _.filter(this.all_suppliers, {'identity_document_type_id': '6'})
                //     this.selectSupplier()

                // } else {
                // this.suppliers =  this.all_suppliers  //_.filter(this.all_suppliers, (c) => { return c.identity_document_type_id !== '6' })
                this.selectSupplier()
                // }
            },
            selectSupplier(){

                let supplier = _.find(this.suppliers, {'id': this.aux_supplier_id})
                this.form.supplier_id = (supplier) ? supplier.id : null
                this.aux_supplier_id = null

            },
            initForm() {
                this.errors = {}
                this.form = {
                    id:null,
                    establishment_id: null,
                    document_type_id: null,
                    prefix:'OC',
                    series: null,
                    number: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    supplier_id: null,
                    payment_method_type_id:'01',
                    currency_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    perception_date: null,
                    perception_number: null,
                    total_perception: 0,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    attached_temp_path: null,
                    attached: null,
                    sale_opportunity_id: null,
                    taxes: [],

                    sale: 0,
                    total_tax: 0,
                    subtotal: 0,
                }

                this.initInputPerson()
                this.fileList = []

            },
            resetForm() {
                this.initForm()
                this.form.establishment_id = this.establishment.id
                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null

                let find_currency = _.find(this.currencies, {id:170})
                this.form.currency_id = find_currency ? find_currency.id: null

                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeCurrencyType()
            },
            changeDateOfIssue() {
                this.form.date_of_due = this.form.date_of_issue
                // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                //     this.form.exchange_rate_sale = (response == 0) ? 1 : response
                // })
            },
            changeDocumentType() {
                this.filterSuppliers()
            },
            addRow(row) {
                // console.log(row)
                this.form.items.push(row)
                this.calculateTotal()
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
            },
            calculateTotal() {

                this.setDataTotals()
                this.calculatePerception()

            },
            calculatePerception(){

                let supplier = _.find(this.all_suppliers,{'id':this.form.supplier_id})

                if(supplier){

                    if(supplier.perception_agent) {

                        let total_perception = 0
                        let quantity_item_perception = 0
                        let total_amount = 0
                        this.form.total_perception = 0

                        this.form.perception_date = moment().format('YYYY-MM-DD')

                        this.form.items.forEach((row) => {
                            quantity_item_perception += (row.item.has_perception) ? 1:0
                            total_perception += (row.item.has_perception) ? (parseFloat(row.unit_price) * parseFloat(row.quantity) * (parseFloat(row.item.percentage_perception)/100)) : 0
                        });

                        this.is_perception_agent = (quantity_item_perception > 0) ? true : false
                        this.form.total_perception = _.round(total_perception,2)
                        total_amount = this.form.total + parseFloat(this.form.total_perception)
                        this.total_amount = _.round(total_amount, 2)

                    }else{

                        this.is_perception_agent = false
                        this.form.perception_date = null
                        this.form.perception_number = null
                        this.form.total_perception = null

                    }

                }


            },
            async submit() {

                this.loading_submit = true
                await this.changePaymentMethodType(false)
                await this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {

                        if (response.data.success) {

                            this.resetForm()
                            this.purchaseNewId = response.data.data.id

                            if(this.saleOpportunity){

                                this.$message.success(`La orden de compra ${response.data.data.number_full} fue generada`)
                                this.close()

                            }else{

                                this.isUpdate()
                                this.showDialogOptions = true
                            }


                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                location.href = '/purchase-orders'
            },
            reloadDataSuppliers(supplier_id) {

                this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {

                    this.aux_supplier_id = supplier_id
                    this.all_suppliers = response.data
                    this.filterSuppliers()

                })
            },
        }
    }
</script>