
<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
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
                                        @keyup.enter.native="keyupCustomer"
                                        :loading="loading_search"
                                        @change="changeCustomer">
                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-3 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.type_invoice_id}">
                                    <label class="control-label">Tipo de factura</label>
                                    <el-select v-model="form.type_invoice_id"  popper-class="el-select-document_type" dusk="type_invoice_id" class="border-left rounded-left border-info" :disabled="true">
                                        <el-option v-for="option in type_invoices" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_invoice_id" v-text="errors.type_invoice_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-3 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.type_invoice_id}">
                                    <label class="control-label">Resolución</label>
                                    <el-select @change="changeResolution" v-model="form.resolution_id"  popper-class="el-select-document_type" dusk="type_invoice_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in resolutions" :key="option.id" :value="option.id" :label="`${option.prefix} / ${option.description} / ${option.resolution_number} / ${option.from} / ${option.to}`"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_invoice_id" v-text="errors.type_invoice_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_issue}">
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="calculate_time_days_credit" :picker-options="datEmision"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_issue" v-text="errors.date_issue[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_expiration}">
                                    <label class="control-label">Fec. Vencimiento</label>
                                    <el-date-picker v-model="form.date_expiration" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="calculate_time_days_credit"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_expiration" v-text="errors.date_expiration[0]"></small>
                                </div>
                            </div>

<!--
                            <div class="col-lg-2" v-show="form.payment_form_id == 2">
                                <div class="form-group" :class="{'has-danger': errors.time_days_credit}">
                                    <label class="control-label">Plazo Credito</label>
                                    <el-input v-model="form.time_days_credit"></el-input>
                                    <small class="form-control-feedback" v-if="errors.time_days_credit" v-text="errors.time_days_credit[0]"></small>
                                </div>
                            </div>  -->

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.time_days_credit}">
                                    <label class="control-label">Plazo Dias</label>
                                    <el-input v-model="form.time_days_credit" :disabled="true"></el-input>
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
                            <template v-if="is_edit">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label">Editando factura</label>
                                        <el-input v-model="form.number" :disabled="true"></el-input>
                                    </div>
                                </div>
                            </template>
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
                                <template v-if="health_sector">
                                    <div>
                                        <p class="text-center"><strong>INFORMACION DE USUARIOS DEL SECTOR SALUD</strong></p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center"><strong>Cod Provee.</strong></th>
                                                    <th class="text-center"><strong>Usuario Id.</strong></th>
                                                    <th class="text-center"><strong>Nombre</strong></th>
                                                    <th class="text-center"><strong>Nro Auth.</strong></th>
                                                    <th class="text-center"><strong>Mipres</strong></th>
                                                    <th class="text-center"><strong>Contrato Poliza</strong></th>
                                                    <th class="text-center"><strong>Copago</strong></th>
                                                    <th class="text-center"><strong>Cuota Mod.</strong></th>
                                                    <th class="text-center"><strong>Cuota Rec.</strong></th>
                                                    <th class="text-center"><strong>Pago Comp.</strong></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="form.health_users.length > 0">
                                                <tr v-for="(row, index) in form.health_users" :key="index">
                                                    <td>{{index + 1}}</td>
                                                    <td>{{row.provider_code}}</td>
                                                    <td class="text-right">{{row.identification_number}}</td>
                                                    <td>{{row.first_name}} {{row.middle_name}} {{row.surname}} {{row.second_surname}}</td>
                                                    <td class="text-right">{{row.autorization_numbers}}</td>
                                                    <td class="text-right">{{row.mipres}}
                                                        <br/>
                                                        <small>Nro Entrega: {{row.mipres_delivery}}</small>
                                                    </td>
                                                    <td class="text-right">{{row.contract_number}}
                                                        <br/>
                                                        <small>Poliza: {{row.policy_number}}</small>
                                                    </td>
                                                    <td class="text-right">{{ratePrefix()}} {{row.co_payment}}</td>
                                                    <td class="text-right">{{ratePrefix()}} {{row.moderating_fee}}</td>
                                                    <td class="text-right">{{ratePrefix()}} {{row.recovery_fee}}</td>
                                                    <td class="text-right">{{ratePrefix()}} {{row.shared_payment}}</td>
                                                    <td class="text-right">
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveUser(index)">x</button>
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click="clickEditUser(row, index)"><span style='font-size:10px;'>&#9998;</span> </button>
                                                    </td>
                                                </tr>
                                                <tr><td colspan="9"></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>


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

                                                <td class="text-right">{{row.quantity}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.quantity"></el-input-number> </td> -->

                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.price)}}</td>
                                                <!--<td class="text-right" v-else ><el-input-number :min="0.01" v-model="row.unit_price"></el-input-number> </td> -->


                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.subtotal)}}</td>
                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.discount)}}</td>
                                                <td class="text-right">{{ratePrefix()}} {{getFormatDecimal(row.total)}}</td>
                                                <td class="text-right">
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click="clickEditItem(row, index)" ><span style='font-size:10px;'>&#9998;</span> </button>
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
                                    <button type="button" class="ml-3 btn waves-effect waves-light btn-primary" @click.prevent="clickAddOrderReference">+ Order Reference</button>
                                    <template v-if="health_sector">
                                        <button type="button" class="ml-3 btn waves-effect waves-light btn-primary" @click.prevent="clickAddHealthData">+ Datos Salud</button>
                                        <button type="button" class="ml-3 btn waves-effect waves-light btn-primary" @click.prevent="clickAddHealthUser">+ Usuarios Salud</button>
                                    </template>
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
                        <el-button @click.prevent="preeliminarview()" :loading="loading_preeliminar_view" v-if="form.items.length > 0">Vista Preeliminar</el-button>
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

        <document-form-retention :showDialog.sync="showDialogAddRetention"
                           @add="addRowRetention"></document-form-retention>

        <document-order-reference :showDialog.sync="showDialogOrderReference"
                            :order_reference="form.order_reference"
                            @addOrderReference="addOrderReference"
                            ></document-order-reference>

        <document-health-data :showDialog.sync="showDialogHealthData"
                            :health_fields="form.health_fields"
                            @addHealthData="addHealthData"
                            ></document-health-data>

        <document-health-user :showDialog.sync="showDialogAddHealthUser"
                            :recordItemHealthUser="recordItemHealthUser"
                            @add="addRowHealthUser"></document-health-user>
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
    import {functions, exchangeRate} from '@mixins/functions'
    // import {calculateRowItem} from '../../../helpers/functions'
    import DocumentOptions from './partials/options.vue'
    import DocumentOrderReference from './partials/order_reference.vue'
    import DocumentHealthData from './partials/health_fields.vue'
    import DocumentHealthUser from './partials/health_users.vue'

    export default {
        props: ['typeUser', 'configuration', 'invoice', 'is_health', 'is_edit'],
        components: {PersonForm, DocumentFormItem, DocumentFormRetention, DocumentOptions, DocumentOrderReference, DocumentHealthData, DocumentHealthUser},
        mixins: [functions, exchangeRate],
        data() {
            return {
                showDialogOrderReference: false,
                showDialogHealthData: false,
                showDialogAddHealthUser: false,
                datEmision: {
                  disabledDate(time) {
                    return time.getTime() > moment();
                  }
                },
                input_person:{},
                company:{},
                health_sector: false,
                is_client: false,
                recordItem: null,
                recordItemHealthUser: null,
                resource: 'co-documents',
                showDialogAddItem: false,
                showDialogAddHealthUser: false,
                showDialogAddRetention: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_preeliminar_view: false,
                loading_form: false,
                errors: {},
                form: {},
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
                resolutions:[],
                duplicated_health_fields: {},
                duplicated_health_users: [],
            }
        },
        async created() {
//            console.log(this.invoice)
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.all_customers = response.data.customers;
                    this.customers = response.data.customers;
                    this.taxes = response.data.taxes
//                    console.log(this.customers)
                    this.type_invoices = response.data.type_invoices;
                    this.currencies = response.data.currencies
                    this.payment_methods = response.data.payment_methods
                    this.payment_forms = response.data.payment_forms
                    this.form.currency_id = (this.currencies.length > 0)?170:null;
                    this.form.type_invoice_id = (this.type_invoices.length > 0)?this.type_invoices[0].id:null;
                    //his.form.payment_form_id = (this.payment_forms.length > 0)?this.payment_forms[0].id:null;
                    this.form.payment_method_id = 10;//(this.payment_methods.length > 0)?this.payment_methods[0].id:null;
                    this.resolutions = response.data.resolutions
                    this.form.payment_form_id = 1
                    // this.selectDocumentType()
                    this.filterCustomers();
                    // this.changeEstablishment()
                    // this.changeDateOfIssue()
                    // this.changeDocumentType()
                    // this.changeCurrencyType()
                    this.load_duplicate_invoice();
                })

            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })
            this.$eventHub.$on('initInputPerson', () => {
                this.initInputPerson()
            })
//            console.log(this.customers)
            await this.generatedFromExternalDocument()
        },
        watch: {
            typeDocuments: {
                // handler(val) {
                //     val.forEach(row => {
                //     if (row.alert_range)
                //         this.$root.$emit("addSnackbarNotification", {
                //         text: `La resolución de ${row.name} esta próxima a vencer por rango.`,
                //         color: "warning"
                //         });
                //     if (row.alert_date)
                //         this.$root.$emit("addSnackbarNotification", {
                //         text: `La resolución de ${row.name} esta próxima a vencer por fecha de vigencia.`,
                //         color: "warning"
                //         });
                //     });
                // },
                // deep: true
            }
        },
        computed: {
            generatedFromPos()
            {
                const form_exceed_uvt = this.$getStorage('form_exceed_uvt')

                if(form_exceed_uvt != undefined && form_exceed_uvt) return true

                return false
            }
        },
        methods:
        {
            generatedFromExternalDocument()
            {
                if(this.generatedFromPos)
                {
                    const form_exceed_uvt = this.$getStorage('form_exceed_uvt')

                    this.form.customer_id = form_exceed_uvt.customer_id
                    this.reloadDataCustomers(this.form.customer_id)
                    this.form.currency_id = form_exceed_uvt.currency_id
                    this.form.type_invoice_id = form_exceed_uvt.type_invoice_id
                    this.form.total_discount = form_exceed_uvt.total_discount
                    this.form.total_tax = form_exceed_uvt.total_tax
                    this.form.subtotal = form_exceed_uvt.subtotal
                    this.form.total = form_exceed_uvt.total
                    this.form.sale = form_exceed_uvt.sale
                    this.form.taxes = form_exceed_uvt.taxes
                    this.form.items = this.prepareItems(form_exceed_uvt.items)
                    this.$removeStorage('form_exceed_uvt')
                }
            },
            prepareItems(items)
            {
                return items.map(row => {

                    row.item = this.prepareIndividualItem(row)
                    row.price = row.unit_price
                    row.id = row.item.id

                    return row
                })
            },
            prepareIndividualItem(row)
            {
                const new_item = row.item

                new_item.presentation = (row.presentation && !_.isEmpty(row.presentation)) ? row.presentation : {}

                return new_item
            },
            calculate_time_days_credit() {
                var f1 = moment(this.form.date_issue)
                var f2 = moment(this.form.date_expiration)
                this.form.time_days_credit = f2.diff(f1, 'days')
                if(this.form.time_days_credit < 0) {
                    this.$message.error('No puede seleccionar una fecha de vencimiento, menor a la fecha de emision ... ');
                    this.form.date_expiration = this.form.date_issue
                    this.form.time_days_credit = 0
                }
                else
                    if(this.form.time_days_credit == 0)
                        this.form.payment_form_id = 1
                    else
                        this.form.payment_form_id = 2
            },

            changeResolution()
            {
                if (typeof this.invoice !== 'undefined') {
                    this.form.type_document_id = this.invoice.type_document_id;
                    this.form.resolution_id = this.invoice.type_document_id;
                }
                const resol = this.resolutions.find(x => x.id == this.form.resolution_id)
//                console.log(this.form.resolution_id)
//                console.log(resol)
                if(resol)
                {
                    this.form.resolution_number = resol.resolution_number
                    this.form.prefix = resol.prefix
                    this.form.type_document_id = resol.id
                }
            },

            ratePrefix(tax = null) {
                if ((tax != null) && (!tax.is_fixed_value)) return null;

                return (this.company.currency != null) ? this.company.currency.symbol : '$';
            },
            keyupCustomer(){

            },
            clickAddItemInvoice(){
                this.recordItem = null
                this.showDialogAddItem = true
            },
            clickAddRetention(){
                this.showDialogAddRetention = true
            },
            clickAddOrderReference(){
                this.showDialogOrderReference = true
            },

            clickAddHealthData() {
                this.showDialogHealthData = true
            },

            clickAddHealthUser(){
                this.recordItemHealthUser = null
                this.showDialogAddHealthUser = true
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

            clickEditItem(row, index)
            {
                row.indexi = index
                this.recordItem = row
                this.showDialogAddItem = true
            },

            clickEditUser(row, index)
            {
                row.indexi = index
                this.recordItemHealthUser = row
                this.showDialogAddHealthUser = true
            },

            searchRemoteCustomers(input) {
                if (input.length > 0) {
                    this.loading_search = true
                    let parameters = `input=${input}`

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

            load_duplicate_invoice(){
                if (typeof this.invoice !== 'undefined') {
                    if(this.invoice.health_fields){
                        this.health_sector = true
                        this.duplicated_health_fields = JSON.parse(this.invoice.health_fields)
                        this.duplicated_health_users = this.duplicated_health_fields.users_info
                        delete this.duplicated_health_fields.users_info
                        delete this.duplicated_health_fields.health_type_operation_id
                    }
                    this.form.type_document_id = this.invoice.type_document_id;
                    this.form.resolution_id = this.invoice.type_document_id;
                    this.form.currency_id = this.invoice ? this.invoice.currency_id : null;
                    this.form.date_issue = this.invoice ? moment(this.invoice.date_of_issue, 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
                    this.form.date_expiration = this.invoice ? moment(this.invoice.date_expiration, 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD') : null;
                    this.form.type_invoice_id = this.invoice ? this.invoice.type_invoice_id : 1;
                    this.form.total_discount = this.invoice ? this.invoice.total_discount : 0;
                    this.form.total_tax = this.invoice ? (this.invoice.total_tax < 0 ? 0 : this.invoice.total_tax) : 0;
                    this.form.customer_id = this.invoice ? this.invoice.customer_id : null,
                    this.form.subtotal = this.invoice ? this.invoice.subtotal : 0;
                    this.form.items = this.invoice ? this.prepareItems(this.invoice.items) : [];
                    this.form.users_info = this.invoice ? this.invoice.users_info : []
                    this.form.taxes = this.invoice ? this.invoice.taxes : [];
                    this.form.total = this.invoice ? this.invoice.total : 0;
                    this.form.sale = this.invoice ? this.invoice.sale : 0;
                    this.form.observation = this.invoice ? this.invoice.observation : null;
                    this.form.time_days_credit = this.invoice ? this.invoice.time_days_credit : 0;
                    this.form.service_invoice = {};
                    this.form.payment_form_id = this.invoice ? this.invoice.payment_form_id : null;
                    this.form.payment_method_id = this.invoice ? this.invoice.payment_method_id : null;
                    this.form.prefix = this.invoice ? this.invoice.prefix : null;
                    this.form.resolution_number = null;
                    this.form.order_reference = this.invoice ? this.invoice.order_reference : {};
                    this.form.health_fields = this.health_sector ? this.duplicated_health_fields : {};
                    this.form.health_users = this.health_sector ? this.duplicated_health_users: [];
                    this.changeResolution();
                    if(this.is_edit){
                        this.form.number = this.invoice.number
                        this.calculateTotal()
                    }
                }
            },

            initForm() {
//                console.log(JSON.stringify(this.invoice))
                if (this.is_health)
                    this.health_sector = true
                else
                    this.health_sector = false
                this.form = {
                    type_document_id: null,
                    resolution_id: null,
                    currency_id: this.invoice ? this.invoice.currency_id : null,
                    date_issue: this.invoice ? moment(this.invoice.date_of_issue, 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD') : moment().format('YYYY-MM-DD'),
                    date_expiration: this.invoice ? moment(this.invoice.date_expiration, 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD') : null,
                    type_invoice_id: this.invoice ? this.invoice.type_invoice_id : 1,
                    total_discount: this.invoice ? this.invoice.total_discount : 0,
                    total_tax: this.invoice ? (this.invoice.total_tax < 0 ? 0 : this.invoice.total_tax) : 0,
                    customer_id: this.invoice ? this.invoice.customer_id : null,
                    watch: false,
                    subtotal: this.invoice ? this.invoice.subtotal : 0,
                    items: this.invoice ? this.prepareItems(this.invoice.items) : [],
                    users_info: this.invoice ? this.invoice.users_info : [],
                    taxes: this.invoice ? this.invoice.taxes : [],
                    total: this.invoice ? this.invoice.total : 0,
                    sale: this.invoice ? this.invoice.sale : 0,
                    observation: this.invoice ? this.invoice.observation : null,
                    time_days_credit: this.invoice ? this.invoice.time_days_credit : 0,
                    service_invoice: {},
                    payment_form_id: this.invoice ? this.invoice.payment_form_id : null,
                    payment_method_id: this.invoice ? this.invoice.payment_method_id : null,
                    resolution_id: null,
                    prefix: this.invoice ? this.invoice.prefix : null,
                    resolution_number: null,
                    order_reference: {},
                    health_fields: {},
                    health_users: []
                }
                if(this.is_edit)
                    this.form.number = this.invoice ? this.invoice.number : null
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
                this.form.currency_id = (this.currencies.length > 0)?170:null
                // this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.form.type_invoice_id = (this.type_invoices.length > 0)?this.type_invoices[0].id:null
                this.form.payment_form_id = (this.payment_forms.length > 0)?this.payment_forms[0].id:null;
                this.form.payment_method_id = (this.payment_methods.length > 0)?this.payment_methods[0].id:null;
                // this.form.operation_type_id = (this.operation_types.length > 0)?this.operation_types[0].id:null
                // this.selectDocumentType()
                // this.changeEstablishment()
                // this.changeDocumentType()
                // this.changeDateOfIssue()
                // this.changeCurrencyType()
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
                this.filterSeries();
                this.cleanCustomer();
                this.filterCustomers();
            },
            cleanCustomer(){
                this.form.customer_id = null
                // this.customers = []
            },
            changeDateOfIssue() {
            //   if(moment(this.form.date_of_issue) < moment().day(-1) && this.configuration.restrict_receipt_date) {
            //     this.$message.error('No puede seleccionar una fecha menor a 6 días.');
            //     this.dateValid=false
            //   } else { this.dateValid = true }
                // this.form.date_expiration = this.form.date_of_issue
                // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                //     this.form.exchange_rate_sale = response
                // })
            },
            assignmentDateOfPayment(){
                this.form.payments.forEach((payment)=>{
                    payment.date_of_payment = this.form.date_of_issue
                })
            },

            filterSeries() {
                // this.form.series_id = null
                // this.series = _.filter(this.all_series, {'establishment_id': this.form.establishment_id,
                //                                          'type_document_id': this.form.type_document_id,
                //                                          'contingency': this.is_contingency});
                // this.form.series_id = (this.series.length > 0)?this.series[0].id:null
            },
            filterCustomers() {
                // this.form.customer_id = null
                // if(this.form.operation_type_id === '0101' || this.form.operation_type_id === '1001') {
                //     if(this.form.type_document_id === '01') {
                //         this.customers = _.filter(this.all_customers, {'identity_type_document_id': '6'})
                //     } else {
                //         if(this.document_type_03_filter) {
                //             this.customers = _.filter(this.all_customers, (c) => { return c.identity_type_document_id !== '6' })
                //         } else {
                //             this.customers = this.all_customers
                //         }
                //     }
                // } else {
                //    this.customers = this.all_customers
                // }
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

            addHealthData(health_fields) {
                this.form.health_fields = health_fields
            },

            addOrderReference(order_reference) {
                this.form.order_reference = order_reference
            },

            addRowHealthUser(row) {
                if(this.recordItemHealthUser)
                    this.form.health_users[this.recordItemHealthUser.indexi] = row
                else
                    this.form.health_users.push(JSON.parse(JSON.stringify(row)));
                this.recordItemHealthUser = null
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

            clickRemoveUser(index) {
                this.form.health_users.splice(index, 1)
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

            async preeliminarview() {
                if(!this.form.resolution_number || !this.form.prefix)
                {
                    return this.$message.error('Debe seleccionar una Resolución')
                }

                if(!this.form.customer_id){
                    return this.$message.error('Debe seleccionar un cliente')
                }

                if(this.health_sector){
                    if(this.form.health_users.length == 0)
                        return this.$message.error('Para facturas del sector salud se debe incluir los datos de al menos un usuario del servicio')
                    if(!this.form.health_fields.invoice_period_start_date || !this.form.health_fields.invoice_period_end_date)
                        return this.$message.error('Para facturas del sector salud debe incluir los datos del periodo de facturacion')
                }

                this.form.service_invoice = await this.createInvoiceService();

                this.loading_preeliminar_view = true
                this.$http.post(`/${this.resource}/preeliminar-view`, this.form).then(response => {
                    if (response.data.success) {
                        var byteCharacters = atob(response.data.base64invoicepdf);
                        var byteNumbers = new Array(byteCharacters.length);
                        for (var i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        var byteArray = new Uint8Array(byteNumbers);
                        var file = new Blob([byteArray], { type: 'application/pdf;base64' });
                        var fileURL = URL.createObjectURL(file);
                        window.open(fileURL, '_blank');
                    }
                    else{
                        this.$message.error(response.data.message);
                    }
                    this.loading_preeliminar_view = false;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_preeliminar_view = false;
                });
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
                // this.customer_addresses = [];
                // let customer = _.find(this.customers, {'id': this.form.customer_id});
                // this.customer_addresses = customer.addresses;
                // if(customer.address)
                // {
                //     this.customer_addresses.unshift({
                //         id:null,
                //         address: customer.address
                //     })
                // }


                /*if(this.customer_addresses.length > 0) {
                    let address = _.find(this.customer_addresses, {'main' : 1});
                    this.form.customer_address_id = address.id;
                }*/
            },

            async submit() {
                if(!this.form.resolution_number || !this.form.prefix)
                {
                    return this.$message.error('Debe seleccionar una Resolución')
                }

                if(!this.form.customer_id){
                    return this.$message.error('Debe seleccionar un cliente')
                }

                if(this.health_sector){
                    if(this.form.health_users.length == 0)
                        return this.$message.error('Para facturas del sector salud se debe incluir los datos de al menos un usuario del servicio')
                    if(!this.form.health_fields.invoice_period_start_date || !this.form.health_fields.invoice_period_end_date)
                        return this.$message.error('Para facturas del sector salud debe incluir los datos del periodo de facturacion')
                }

                this.form.service_invoice = await this.createInvoiceService();
                // return

                this.loading_submit = true
//                console.log(JSON.stringify(this.form))
                this.$http.post(`/${this.resource}`, this.form).then(response => {
//                    console.log(response)
                    if (response.data.success) {
                        if(response.data.validation_errors === false){
                          this.resetForm();
                          // console.log(response)
                          this.documentNewId = response.data.data.id;
                          this.$message.success(response.data.message);
                          this.showDialogOptions = true;
                        }
                        else{
                            if(response.data.errors){
                                const mhtl = this.parseMesaageError(response.data.errors)
                                this.$message({
                                    duration: 6000,
                                    type: 'error',
                                    dangerouslyUseHTMLString: true,
                                    message: mhtl
                                });
                            }
                            else
                                if(response.data.error){
                                    const ht = `<strong>${response.data.message}</strong> <br> <strong>${response.data.error.string} </strong> `
                                    this.$message({
                                        duration: 6000,
                                        type: 'error',
                                        dangerouslyUseHTMLString: true,
                                        message: ht
                                    });
                                }
                                else{
                                    this.$message.error(response.data.message);
                                }
                                setTimeout(this.close(), 5000)
                        }
                    }
                    else {
                        if(response.data.errors){
                            const mhtl = this.parseMesaageError(response.data.errors)
                            this.$message({
                                duration: 6000,
                                type: 'error',
                                dangerouslyUseHTMLString: true,
                                message: mhtl
                            });
                        }
                        else if(response.data.error){
                            const ht = `<strong>${response.data.message}</strong> <br> <strong>${response.data.error.string} </strong> `
                            this.$message({
                                duration: 6000,
                                type: 'error',
                                dangerouslyUseHTMLString: true,
                                message: ht
                            });
                        }
                        else{
                            this.$message.error(response.data.message);
                        }
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

            parseMesaageError(errors)
            {
                let ht = `Validación de datos <br><br> <ul>`
                for(var key in errors) {
                    //var value = objects[key];
                    ht += `<li>${key}: ${errors[key][0]}</li>`
                }

                ht += `</ul>`

                return ht
            },

            async createInvoiceService() {
                // let resol = this.resolution.resolution; //TODO
                let invoice = {
                    number: 0,
                    type_document_id: 1,
                    prefix: this.form.prefix,
                    resolution_number: this.form.resolution_number,

                };

                invoice.customer =  this.getCustomer();
                invoice.tax_totals = await this.getTaxTotal();
                invoice.legal_monetary_totals = await this.getLegacyMonetaryTotal();
                invoice.allowance_charges = await this.createAllowanceCharge(invoice.legal_monetary_totals.allowance_total_amount, invoice.legal_monetary_totals.line_extension_amount );

                invoice.invoice_lines = await this.getInvoiceLines();
                invoice.with_holding_tax_total = await this.getWithHolding();

                return invoice;
            },
            getCustomer() {
                let customer = this.customers.find(x => x.id == this.form.customer_id);
                let obj = {
                    identification_number: customer.number,
                    name: customer.name,
                    phone: customer.telephone,
                    address: customer.address,
                    email: customer.email,
                    merchant_registration: "000000",
                    type_document_identification_id: customer.identity_document_type_id,
                    type_organization_id: customer.type_person_id,
                    municipality_id_fact: customer.city_id,
                    type_regime_id: customer.type_regime_id,
                    type_liability_id: customer.type_obligation_id
                };

                this.form.customer_id = customer.id

                if (customer.type_person_id == 1) {
                    obj.dv = customer.dv;
                }
                return obj;
            },

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

            getInvoiceLines() {
                let data = this.form.items.map(x => {
                    if(x.tax !== undefined){
                        return {
                            unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                            invoiced_quantity: x.quantity,
                            line_extension_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                            notes: x.notes,
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
                        }
                    }
                    else
                    {
                        return {
                            unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                            invoiced_quantity: x.quantity,
                            line_extension_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                            notes: x.notes,
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
                let total_iva = this.form.total_tax
                let total = this.form.sale
                let list = this.form.taxes.filter(function(x) {
                    return x.is_retention && x.apply;
                });
                return list.map(x => {
                    return {
                        tax_id: x.type_tax_id,
                        tax_amount: this.cadenaDecimales(x.retention),
                        percent: this.cadenaDecimales(this.roundNumber(x.rate / (x.conversion / 100), 6)),
                        taxable_amount: x.in_base ? this.cadenaDecimales(total) : this.cadenaDecimales(total_iva),
                    };
                });

            },
            roundNumber(num, decimales = 2) {
                var signo = (num >= 0 ? 1 : -1);
                num = num * signo;
                if (decimales === 0) //con 0 decimales
                    return signo * Math.round(num);
                // round(x * 10 ^ decimales)
                num = num.toString().split('e');
                num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
                // x * 10 ^ (-decimales)
                num = num.toString().split('e');
                return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
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
