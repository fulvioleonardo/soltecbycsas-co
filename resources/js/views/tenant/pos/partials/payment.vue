<template >
    <div class="row col-lg-12 m-0 p-0" v-loading="loading_submit">
        <div class="col-lg-4 col-md-6 bg-white m-0 p-0" style="height: calc(100vh - 110px)">
            <div class="h-75 bg-light" style="overflow-y: auto">

                <div class="row pl-3 py-2 border-bottom m-0 p-0 bg-white">
                    <div class="col-12 px-0 py-3">
                        <h4 class="font-weight-semibold m-0">{{customer.description}}</h4>
                    </div>
                </div>

                 <template v-for="(item,index) in form.items">
                    <div class="row py-1 border-bottom m-0 p-0" :key="index">
                        <div class="col-2 p-r-0 m-l-2">
                            <h4 class="font-weight-semibold m-0 text-center">{{item.quantity}}</h4>

                        </div>
                        <div class="col-6 px-0">
                            <h4 class="font-weight-semibold m-0 text-center m-b-0">{{item.item.name}}</h4>
                            <!-- <p class="m-b-0">Descripción del producto</p> -->
                            <!-- <p class="text-muted m-b-0"><small>Descuento 2%</small></p> -->
                        </div>
                        <div class="col-4 p-l-0">
                            <!-- <p class="font-weight-semibold m-b-0">{{currencyTypeActive.symbol}} 240.00</p> -->
                            <h4 class="font-weight-semibold m-0 text-center">{{currencyTypeActive.symbol}} {{item.total}}</h4>
                        </div>
                    </div>
                </template>


            </div>
            <div class="h-25 bg-info" style="overflow-y: auto">
                <div class="row m-0 p-0 bg-white h-10 d-flex align-items-center">
                    <div class="col-sm-6 py-1">
                        <p class="font-weight-semibold mb-0">TOTAL VENTA</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{ form.sale }}</p>
                    </div>
                </div>
                <div class="row m-0 p-0 bg-white h-10 d-flex align-items-center" v-if="form.total_discount > 0">
                    <div class="col-sm-6 py-1 ">
                        <p class="font-weight-semibold mb-0">TOTAL DESCUENTO (-)</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{form.total_discount}}</p>
                    </div>
                </div>

                <template v-for="(tax, index) in form.taxes" >
                    <div class="row m-0 p-0 bg-white h-10 d-flex align-items-center" v-if="((tax.total > 0) && (!tax.is_retention))" :key="index" >
                        <div class="col-sm-8 py-1">
                            <p class="font-weight-semibold mb-0">{{tax.name}}(+)</p>
                        </div>
                        <div class="col-sm-4 py-1 text-right">
                            <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{Number(tax.total).toFixed(2)}}</p>
                        </div>
                    </div>
                </template>

                <div class="row m-0 p-0 bg-white h-10 d-flex align-items-center" v-if="form.subtotal > 0">
                    <div class="col-sm-6 py-1">
                        <p class="font-weight-semibold mb-0">SUBTOTAL</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{form.subtotal}}</p>
                    </div>
                </div>

                <div class="row m-0 p-0 h-50 d-flex align-items-center">
                    <div class="col-sm-6 py-2">
                        <p class="font-weight-semibold mb-0 text-white">TOTAL</p>
                    </div>
                    <div class="col-sm-6 py-2 text-right">
                        <p class="font-weight-semibold mb-0 text-white">{{currencyTypeActive.symbol}} {{ form.total }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 px-4 pt-3 hyo">
            <div class="row d-flex justify-content-center pt-2">
                <div class="col-md-12 col-lg-8">
                    <div class="card card-default">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-8">
                                    <el-radio-group v-model="form.document_type_id" size="small" @change="filterSeries(form.document_type_id)">
                                        <template v-for="(resource, index) in resources">
                                            <el-radio-button :label="resource.type">{{ resource.description }}</el-radio-button>
                                        </template>
                                    </el-radio-group>
                                </div>
                                <div class="col-md-4 ">
                                    <button class="text-center btn btn-sm btn-block btn-primary pull-right" @click="back"><i class="fas fa-angle-left"></i> Regresar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                        <label class="control-label">Fecha de emisión</label>
                                        <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                        <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-4" v-show="['COT', 'RM'].includes(form.document_type_id)">
                                    <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                        <label class="control-label">Tiempo de Validez</label>
                                        <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                        <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                                    </div>
                                </div>
                                <div class="col-lg-4" v-show="['COT', 'RM'].includes(form.document_type_id)">
                                    <div class="form-group" :class="{'has-danger': errors.delivery_date}">
                                        <label class="control-label">Tiempo de Entrega</label>
                                        <el-date-picker v-model="form.delivery_date" type="date" value-format="yyyy-MM-dd" :clearable="true"></el-date-picker>
                                        <small class="form-control-feedback" v-if="errors.delivery_date" v-text="errors.delivery_date[0]"></small>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4" v-if="form.document_type_id == '80'">
                                    <el-select v-model="form.series_id" class="c-width" >
                                        <el-option   v-for="option in series" :key="option.id" :label="option.number" :value="option.id">
                                        </el-option>
                                    </el-select>
                                </div>
                                <div class="col-lg-6" v-show="['COT', 'RM'].includes(form.document_type_id)">
                                    <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                        <label class="control-label">Descripción
                                        </label>
                                        <el-input  type="textarea"  :rows="3" v-model="form.description"></el-input>
                                        <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2" v-if="form.document_type_id == '01'">

                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.type_document_id}">
                                        <label class="control-label">Tipo de factura</label>
                                        <el-select v-model="form.type_document_id" >
                                            <el-option v-for="option in type_invoices" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.type_document_id" v-text="errors.type_document_id[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.payment_form_id}">
                                        <label class="control-label">Forma de pago</label>
                                        <el-select v-model="form.payment_form_id" filterable>
                                            <el-option v-for="option in payment_forms" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.payment_form_id" v-text="errors.payment_form_id[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.payment_method_id}">
                                        <label class="control-label">Medio de pago</label>
                                        <el-select v-model="form.payment_method_id" filterable>
                                            <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6" v-show="form.payment_form_id == 2">
                                    <div class="form-group" :class="{'has-danger': errors.time_days_credit}">
                                        <label class="control-label">Plazo Crédito</label>
                                        <el-input v-model="form.time_days_credit"></el-input>
                                        <small class="form-control-feedback" v-if="errors.time_days_credit" v-text="errors.time_days_credit[0]"></small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-body text-center">
                            <p class="my-0"><small>Monto a cobrar</small></p>
                            <h1 class="mb-2 mt-0">{{currencyTypeActive.symbol}} {{ Number(form.total).toFixed(2) }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-body text-center">
                            <div class="row col-lg-12">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">Ingrese monto</label>
                                        <el-input v-model="enter_amount" @input="enterAmount()" >
                                            <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                                        </el-input>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group" :class="{'has-danger': difference < 0}">
                                        <label class="control-label" v-text="(difference <0) ? 'Faltante' :'Vuelto'"></label>
                                        <!-- <el-input v-model="difference" :disabled="true">
                                            <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                                        </el-input> -->
                                        <h4 class="control-label font-weight-semibold m-0 text-center m-b-0">{{currencyTypeActive.symbol}} {{ Number(difference).toFixed(2)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-body">
                            <!-- <p class="text-center">Método de Pago</p> -->
                            <div class="input-group mb-3">
                                <div class="col-lg-12 m-bottom">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <h5><strong>Pagos agregados </strong></h5>
                                        </div>
                                        <div class="col-lg-1">
                                        </div>
                                        <div class="col-lg-5">
                                            <button class="btn btn-sm btn-block btn-primary" @click="clickAddPayment()"><i class="fas fa-plus"></i> Agregar</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 m-bottom" >
                                    <div class="row">
                                        <template v-for="(pay,index) in form.payments">
                                            <div class="col-lg-1" :key="pay.id">
                                                <label>{{index + 1}}.-</label>
                                            </div>
                                            <div class="col-lg-6" :key="pay.id">
                                                <label>{{getDescriptionPaymentMethodType(pay.payment_method_type_id)}}</label>
                                            </div>
                                            <div class="col-lg-5" :key="pay.id">
                                                <label><strong>{{currencyTypeActive.symbol}} {{pay.payment}}</strong> </label>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-lg-12" v-if="form_payment.payment_method_type_id=='01'">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmountCash(10000)">{{currencyTypeActive.symbol}}10.000</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmountCash(20000)" >{{currencyTypeActive.symbol}}20.000</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmountCash(50000)"  >{{currencyTypeActive.symbol}}50.000</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary"  @click="setAmountCash(100000)" >{{currencyTypeActive.symbol}}100.000</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-block btn-primary" @click="clickPayment" :disabled="button_payment">PAGAR</button>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn btn-block btn-danger" @click="clickCancel">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <options-form
            :showDialog.sync="showDialogOptions"
            :recordId="documentNewId"
            :statusDocument="statusDocument"
            :resource="resource_options"
            ></options-form>

        <multiple-payment-form
            :showDialog.sync="showDialogMultiplePayment"
            :payments="payments"
            @add="addRow"
            ></multiple-payment-form>

        <!--<sale-notes-options :showDialog.sync="showDialogSaleNote"
                          :recordId="saleNotesNewId"
                          :originPos="true"
                          :showClose="true"></sale-notes-options>-->

        <card-brands-form :showDialog.sync="showDialogNewCardBrand"
            :external="true"
            :recordId="null"></card-brands-form>

         <document-pos-options :showDialog.sync="showDialogSaleNote"
            :recordId="saleNotesNewId"
            :originPos="true"
            :showClose="true"></document-pos-options>

        <quotation-options :showDialog.sync="showDialogQuotationOptions"
            :recordId="saleNotesNewId"
            :typeUser="'admin'"
            :showGenerate="false"
            :showClose="true"
            @triggerBack="back"></quotation-options>

        <remission-options :showDialog.sync="showDialogRemissionOptions"
            :recordId="saleNotesNewId"
            :showDownload="true"
            :showClose="true"
            @triggerBack="back"></remission-options>
    </div>
</template>
<style>
.c-width{
    width: 80px!important;
    padding: 0!important;
    margin-right: 0!important;
}

</style>

<script>

    import CardBrandsForm from '../../card_brands/form.vue'
    import SaleNotesOptions from '../../sale_notes/partials/options.vue'
    import OptionsForm from './options.vue'
    import MultiplePaymentForm from './multiple_payment.vue'
    import DocumentPosOptions from './document_pos_options.vue'
    import QuotationOptions from '../../quotations/partials/options.vue'
    import RemissionOptions from '@viewsModuleSale/co-remissions/partials/options.vue'

    export default {
        components: {OptionsForm, CardBrandsForm, SaleNotesOptions, MultiplePaymentForm, DocumentPosOptions, QuotationOptions, RemissionOptions},

        props:['form','customer', 'currencyTypeActive', 'exchangeRateSale', 'is_payment', 'soapCompany', 'items_refund'],
        data() {
            return {
                enabled_discount: false,
                discount_amount:0,
                loading_submit: false,
                showDialogOptions:false,
                showDialogMultiplePayment:false,
                showDialogSaleNote:false,
                showDialogNewCardBrand:false,
                showDialogQuotationOptions: false,
                showDialogRemissionOptions: false,
                documentNewId:null,
                saleNotesNewId:null,
                resource_options:null,
                has_card: false,
                resource: 'pos',
                resource_documents: 'document-pos',
                resource_payments: 'document_payments',
                amount: 0,
                enter_amount: 0,
                difference: 0,
                button_payment: false,
                input_item: '',
                form_payment:{},
                series:[],
                all_series:[],
                cards_brand:[],
                cancel:false,
                form_cash_document:{},
                statusDocument:{},
                payment_method_types:[],
                payments:[],
                type_invoices: [],
                type_documents: [],
                payment_methods: [],
                payment_forms: [],
                errors: {},
                limit_uvt: 0,
                resources: [
                    {
                        type: '90',
                        url: 'document-pos',
                        description: 'POS'
                    },
                    {
                        type: 'COT',
                        url: 'quotations',
                        description: 'Cotización'
                    },
                    {
                        type: 'RM',
                        url: 'co-remissions',
                        description: 'Remisión'
                    }
                ]
            }
        },
        async created() {

            await this.initLStoPayment()
            await this.getTables()
            this.initFormPayment()
            this.inputAmount()
            this.form.payments = []
            this.$eventHub.$on('reloadDataCardBrands', (card_brand_id) => {
                this.reloadDataCardBrands(card_brand_id)
            })

            this.$eventHub.$on('localSPayments', (payments) => {
                this.payments = payments

                //inciaalizo el pago el total
                this.enter_amount = parseFloat( this.form.total)
                this.enterAmount()
            })

            await this.getFormPosLocalStorage()
            // console.log(this.form.payments, this.payments)
        },
        mounted(){
        },
        methods: {
            changeEnabledDiscount(){

            },
            inputDiscountAmount(){

            },
            back()
            {
                this.$emit('update:is_payment', false)
            },
            async initLStoPayment(){

                this.amount = await this.getLocalStoragePayment('amount', 0)
                this.enter_amount = await this.getLocalStoragePayment('enter_amount', 0)
                this.difference = await this.getLocalStoragePayment('difference', 0)
            },
            getFormPosLocalStorage(){

                let form_pos = localStorage.getItem('form_pos');
                form_pos = JSON.parse(form_pos)
                if (form_pos) {
                    this.form.payments = form_pos.payments
                }

            },
            clickAddPayment(){
                this.showDialogMultiplePayment = true
            },
            reloadDataCardBrands(card_brand_id) {
                this.$http.get(`/${this.resource}/table/card_brands`).then((response) => {
                    this.cards_brand = response.data
                    this.form_payment.card_brand_id = card_brand_id
                    this.changePaymentMethodType()
                })
            },
            getDescriptionPaymentMethodType(id){
                let payment_method_type = _.find(this.payment_method_types,{'id':id})
                return (payment_method_type) ? payment_method_type.description:''

            },
            changePaymentMethodType(){
                let payment_method_type = _.find(this.payment_method_types,{'id':this.form_payment.payment_method_type_id})
                this.has_card = payment_method_type.has_card
                this.form_payment.card_brand_id = (payment_method_type.has_card) ? this.form_payment.card_brand_id:null
            },
            addRow(payments) {

                this.form.payments = payments
                let acum_payment = 0

                this.form.payments.forEach((item)=>{
                    acum_payment += parseFloat(item.payment)
                })

               // this.amount = acum_payment
                this.setAmount(acum_payment)

                // console.log(this.form.payments)
            },
            setAmount(amount){
                // this.amount = parseFloat(this.amount) + parseFloat(amount)
                this.amount =  parseFloat(amount) //+ parseFloat(amount)
                this.enter_amount =  parseFloat(amount).toFixed(3) //+ parseFloat(amount)
                this.inputAmount()
            },
            setAmountCash(amount)
            {

                let row = _.last(this.payments, { 'payment_method_type_id' : '01' })
                row.payment = parseFloat(row.payment) + parseFloat(amount)
                // console.log(row.payment)

                this.form.payments = this.payments
                let acum_payment = 0

                this.form.payments.forEach((item)=>{
                    acum_payment += parseFloat(item.payment)
                })

                this.setAmount(acum_payment)

            },
            async enterAmount(){

                let r_item = await _.last(this.payments, { 'payment_method_type_id' : '01' })
                r_item.payment = await parseFloat(this.enter_amount)
                // console.log(r_item.payment)

                let ind = this.form.payments.length - 1
                this.form.payments[ind].payment = parseFloat(this.enter_amount)
                // this.setAmount(item.payment)

                let acum_payment = 0

                await this.form.payments.forEach((item)=>{
                    acum_payment += parseFloat(item.payment)
                })
                // console.log(this.form.payments)

                // this.amount = item.payment
                this.amount = acum_payment
                // this.amount = this.enter_amount
                // console.log(this.amount)
                this.difference = this.amount - this.form.total

                if(isNaN(this.difference)) {
                    this.button_payment = true
                    this.difference = "-"
                }else if(this.difference >=0){
                    this.button_payment = false
                    this.difference = this.amount - this.form.total
                }else{
                    this.button_payment = true
                }
                this.difference = _.round(this.difference,2)

                this.$eventHub.$emit('eventSetFormPosLocalStorage', this.form)

                await this.lStoPayment()

            },
            getLocalStoragePayment(key, re_default = null){

                let ls_obj = localStorage.getItem(key);
                ls_obj = JSON.parse(ls_obj)

                if (ls_obj) {
                    return ls_obj
                }

                return re_default
            },
            setLocalStoragePayment(key, obj){
                localStorage.setItem(key, JSON.stringify(obj));
            },
            inputAmount(){

                this.difference = this.amount - this.form.total

                if(isNaN(this.difference)) {
                    this.button_payment = true
                    this.difference = "-"
                }else if(this.difference >=0){
                    this.button_payment = false
                    this.difference = this.amount - this.form.total
                }else{
                    this.button_payment = true
                }
                this.difference = _.round(this.difference,2)
                // this.form_payment.payment = this.amount

                this.$eventHub.$emit('eventSetFormPosLocalStorage', this.form)
                this.lStoPayment()

            },
            lStoPayment(){

                this.setLocalStoragePayment('enter_amount', this.enter_amount)
                this.setLocalStoragePayment('amount', this.amount)
                // console.log(this.amount)
                this.setLocalStoragePayment('difference', this.difference)

            },
            initFormPayment() {

                this.difference = -this.form.total
                this.form_payment = {
                    id: null,
                    date_of_payment: moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    card_brand_id:null,
                    document_id:null,
                    sale_note_id:null,
                    payment: this.form.total,
                }

                this.form_cash_document = {
                    document_id:null,
                    sale_note_id:null,
                    document_pos_id:null
                }
            },

            filterSeries(document_type_id) {
                let resource = this.resources.find(function (element) { return element.type === document_type_id })
                this.resource_documents = resource.url
                this.form.document_type_id = resource.type
                this.form.prefix = resource.type
                // this.form.document_type_id =
                // this.form.document_type_id = '90'
                /*this.form.series_id = null
                this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id });
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null


                if(!this.form.series_id && this.form.document_type_id == '80')
                {
                   return this.$message.warning('El establecimiento no tiene series disponibles para el comprobante');
                }*/
            },
            async clickCancel(){

                this.loading_submit = true
                await this.sleep(800);
                this.loading_submit = false
                this.cleanLocalStoragePayment()
                this.$eventHub.$emit('cancelSale')

            },
            cleanLocalStoragePayment(){

                this.setLocalStoragePayment('amount', null)
                this.setLocalStoragePayment('enter_amount', null)
                this.setLocalStoragePayment('difference', null)
            },
            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },
            validateLimitUvt()
            {
                if(this.limit_uvt > 0)
                {
                    if(parseFloat(this.form.sale) >= this.limit_uvt)
                    {
                        return {
                            success: false,
                            message: `El valor del documento (${this.currencyTypeActive.symbol} ${this.form.sale}) ha superado el límite configurado para la UVT (${this.currencyTypeActive.symbol} ${this.limit_uvt}), debe generar factura.`
                        }
                    }
                }

                return {
                    success: true
                }
            },
            async redirectCreateDocument()
            {
                this.loading_submit = true
                await this.sleep(2000)
                this.loading_submit = false
                window.open('/co-documents/create', '_blank')
            },

            async clickPayment(){
                const validate_limit_uvt = this.validateLimitUvt()

                if(!validate_limit_uvt.success){
                    this.$setStorage('form_exceed_uvt', this.form)
                    this.$message.error({
                        showClose: true,
                        message: validate_limit_uvt.message,
                        type: 'error',
                        duration: 6000
                    })
                    this.redirectCreateDocument()
                    return
                }
                //this.form.prefix = "NV";
                this.form.paid = 1;
                // this.resource_documents = resources[this.form.document_type_id];
                this.resource_payments = "document_pos_payments";
                this.resource_options = this.resource_documents;
                // if(this.form.document_type_id === 'COT') {
                //     this.form.prefix = this.form.document_type_id
                // }
                // if(this.form.document_type_id === 'RM') {
                //     this.form.prefix = this.form.document_type_id
                // }
                const items_final = this.form.items.concat(this.items_refund);
                this.form.items = items_final
                this.loading_submit = true
                this.$http.post(`/${this.resource_documents}`, this.form).then(response => {
                    if (response.data.success == true) {
                        this.form_cash_document.document_pos_id = response.data.data.id;
                        this.saleNotesNewId = response.data.data.id;
                        if(this.saleNotesNewId != null){
                            if (this.form.document_type_id === "90") {
                                this.showDialogSaleNote = true;
                            }
                            if (this.form.document_type_id === "COT") {
                                this.showDialogQuotationOptions = true;
                            }
                            if (this.form.document_type_id === "RM") {
                                this.showDialogRemissionOptions = true;
                            }

                            /*if (this.form.document_type_id === "80") {
                                // this.form_payment.sale_note_id = response.data.data.id;
                                this.form_cash_document.sale_note_id = response.data.data.id;
                                this.saleNotesNewId = response.data.data.id;
                                this.showDialogSaleNote = true;
                            } else {
                                // this.form_payment.document_id = response.data.data.id;
                                this.form_cash_document.document_id = response.data.data.id;
                                this.statusDocument = response.data.data.response
                                this.documentNewId = response.data.data.id;
                                this.showDialogOptions = true;
                            }*/
                            // this.savePaymentMethod();
                            this.saveCashDocument();
                            // this.initFormPayment() ;
                            this.cleanLocalStoragePayment()
                            this.$message.success(response.data.message);
                            this.$eventHub.$emit('saleSuccess');
                        }
                        else
                            this.$message.error(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response && error.response.status === 422){
                        this.errors = error.response.data;
                    }else if (error.response){
                        this.$message.error(error.response.data.message);
                    }else{
                        this.$message.error("Ha ocurrido un error desconocido.");
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },

            saveCashDocument(){
                if(this.form.document_type_id !== "90") {
                    return
                }
                this.$http.post(`/cash/cash_document`, this.form_cash_document)
                    .then(response => {
                        if (response.data.success) {
                            // console.log(response)
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            savePaymentMethod(){
                this.$http.post(`/${this.resource_payments}`, this.form_payment)
                    .then(response => {
                        if (response.data.success) {
                            // console.log(response)
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.records[index].errors = error.response.data;
                        } else {
                            console.log(error);
                        }
                    })
            },
            getTables(){
                this.$http.get(`/${this.resource}/payment_tables`)
                    .then(response => {
                        this.all_series = response.data.series
                        this.payment_method_types = response.data.payment_method_types
                        this.cards_brand = response.data.cards_brand
                        this.type_invoices = response.data.type_invoices
                        this.type_documents = response.data.type_documents
                        this.payment_methods = response.data.payment_methods
                        this.payment_forms = response.data.payment_forms
                        this.limit_uvt = response.data.limit_uvt
                        this.filterSeries(this.form.document_type_id)
                    })
            },

            async createInvoiceService() {
                // let resol = this.resolution.resolution; //TODO
                const invoice = {
                    number: 0,
                    type_document_id: 1
                };

                invoice.customer = await this.getCustomer();
                invoice.tax_totals = await this.getTaxTotal();
                invoice.legal_monetary_totals = await this.getLegacyMonetaryTotal();
                invoice.allowance_charges = await this.createAllowanceCharge(invoice.legal_monetary_totals.allowance_total_amount, invoice.legal_monetary_totals.line_extension_amount );

                invoice.invoice_lines = await this.getInvoiceLines();
                invoice.with_holding_tax_total = await this.getWithHolding();

                return invoice;
            },
            getCustomer() {

                let customer = this.customer;

                let obj = {
                    identification_number: customer.number,
                    name: customer.name,
                    phone: customer.telephone,
                    address: customer.address,
                    email: customer.email,
                    merchant_registration: "000000"
                };

                this.form.customer_id = customer.id

                if (customer.type_person_id == 2) {
                    obj.dv = customer.dv;
                }

                return obj;
            },

            getTaxTotal() {

                let tax = [];
                this.form.items.forEach(element => {
                    let find = tax.find(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
                    if(find)
                    {
                        let indexobj = tax.findIndex(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
                        tax.splice(indexobj, 1);
                        tax.push({
                            tax_id: find.tax_id,
                            tax_amount: this.cadenaDecimales(Number(find.tax_amount) + Number(element.total_tax)),
                            percent: this.cadenaDecimales(find.percent),
                            taxable_amount: this.cadenaDecimales(Number(find.taxable_amount) + Number(element.unit_price) * Number(element.quantity)) - Number(element.discount)
                        });
                    }
                    else {
                        tax.push({
                            tax_id: element.tax.type_tax_id,
                            tax_amount: this.cadenaDecimales(Number(element.total_tax)),
                            percent: this.cadenaDecimales(Number(element.tax.rate)),
                            taxable_amount: this.cadenaDecimales((Number(element.unit_price) * Number(element.quantity)) - Number(element.discount))
                        });
                    }
                });
            //      console.log(tax);
                this.tax_amount_calculate = tax;
                return tax;
            },

            getLegacyMonetaryTotal() {

                let line_ext_am = 0;
                let tax_incl_am = 0;
                let allowance_total_amount = 0;
                this.form.items.forEach(element => {
                    line_ext_am += (Number(element.unit_price) * Number(element.quantity)) - Number(element.discount);
                    allowance_total_amount += Number(element.discount);
                });

                let total_tax_amount = 0;
                this.tax_amount_calculate.forEach(element => {
                    total_tax_amount += Number(element.tax_amount);
                });

                tax_incl_am = line_ext_am + total_tax_amount;

                return {
                    line_extension_amount: this.cadenaDecimales(line_ext_am),
                    tax_exclusive_amount: this.cadenaDecimales(line_ext_am),
                    tax_inclusive_amount: this.cadenaDecimales(tax_incl_am),
                    allowance_total_amount: this.cadenaDecimales(allowance_total_amount),
                    charge_total_amount: "0.00",
                    payable_amount: this.cadenaDecimales(tax_incl_am - allowance_total_amount)
                };

            },

            getInvoiceLines() {

                let data = this.form.items.map(x => {
                    return {

                        unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                        invoiced_quantity: x.quantity,
                        line_extension_amount: this.cadenaDecimales((Number(x.unit_price) * Number(x.quantity)) - x.discount),
                        free_of_charge_indicator: false,
                                allowance_charges: [
                            {
                                        charge_indicator: false,
                                        allowance_charge_reason: "DESCUENTO GENERAL",
                                        amount: this.cadenaDecimales(x.discount),
                                        base_amount: this.cadenaDecimales(Number(x.unit_price) * Number(x.quantity))
                                    }
                        ],
                        tax_totals: [
                            {
                                tax_id: x.tax.type_tax_id,
                                tax_amount: this.cadenaDecimales(x.total_tax),
                                taxable_amount: this.cadenaDecimales((Number(x.unit_price) * Number(x.quantity)) - x.discount),
                                percent: this.cadenaDecimales(x.tax.rate)
                            }
                        ],
                        description: x.item.name,
                        code: x.item.internal_id,
                        type_item_identification_id: 4,
                        price_amount: this.cadenaDecimales(x.unit_price),
                        base_quantity: x.quantity
                    };

                });

                return data;
            },

            getWithHolding() {

                let total = this.form.sale
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

            cadenaDecimales(amount){
                if(amount.toString().indexOf(".") != -1)
                    return amount.toString();
                else
                    return amount.toString()+".00";
                },
            }

    }
</script>
