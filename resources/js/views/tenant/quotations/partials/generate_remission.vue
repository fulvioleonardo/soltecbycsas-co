<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
    
            <div class="row"> 

                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                        <label class="control-label">Fecha de emisión</label>
                        <el-date-picker v-model="document.date_of_issue" type="date" value-format="yyyy-MM-dd" @change="changeDateOfIssue" :clearable="false"></el-date-picker>
                        <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.date_expiration}">
                        <label class="control-label">Fecha de vencimiento</label>
                        <el-date-picker v-model="document.date_expiration" type="date" value-format="yyyy-MM-dd" ></el-date-picker>
                        <small class="form-control-feedback" v-if="errors.date_expiration" v-text="errors.date_expiration[0]"></small>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.payment_form_id}">
                        <label class="control-label">Forma de pago</label>
                        <el-select v-model="document.payment_form_id" filterable>
                            <el-option v-for="option in payment_forms" :key="option.id" :value="option.id" :label="option.name"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.payment_form_id" v-text="errors.payment_form_id[0]"></small>
                    </div>
                </div>
                
                <div class="col-lg-6" v-show="document.payment_form_id == 2">
                    <div class="form-group" :class="{'has-danger': errors.time_days_credit}">
                        <label class="control-label">Plazo Credito</label>
                        <el-input v-model="document.time_days_credit"></el-input>
                        <small class="form-control-feedback" v-if="errors.time_days_credit" v-text="errors.time_days_credit[0]"></small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.payment_method_id}">
                        <label class="control-label">Medio de pago</label>
                        <el-select v-model="document.payment_method_id" filterable>
                            <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                    </div>
                </div>


            </div>

            <span slot="footer" class="dialog-footer">
                <el-button @click="clickClose">Cerrar</el-button>
                <el-button class="submit" type="primary" @click="submit" :loading="loading_submit">Generar</el-button>
            </span>
        </el-dialog>

        <remission-options 
            :showDialog.sync="showDialogRemissionOptions" 
            :recordId="remissionNewId" 
            :showClose="true"
        ></remission-options>

    </div>
</template>

<script>

    import RemissionOptions from '@viewsModuleSale/co-remissions/partials/options.vue'

    export default {
        components: {
            RemissionOptions,
        },

        props: ["showDialog", "recordId"],
        data() {
            return {
                titleDialog: null,
                resource: "quotations",
                resource_remissions: "co-remissions",
                errors: {},
                form: {},
                document: {},
                loading_submit: false,
                showDialogRemissionOptions: false,
                remissionNewId: null,
                payment_methods: [],
                payment_forms: [],
            };
        },
        created() {
            this.initForm();
            this.initDocument();
        },
        methods: {
            initForm() {

                this.errors = {};
                this.form = {
                    id: null,
                    external_id: null,
                    identifier: null,
                    date_of_issue: null,
                    has_document: false,
                    quotation: null
                };
                
            }, 
            initDocument() {
                
                this.document = {
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
                    payment_form_id: 1,
                    payment_method_id: 10,
                    prefix: 'RM',
                    number: null,

                };
            },
            changeDateOfIssue() {
                this.document.date_expiration = this.document.date_of_issue
            },
            resetDocument() {
                this.initDocument();
            },
            async submit() {

                this.loading_submit = true;
                await this.assignDocument();
                // console.log(this.document)

                await this.$http
                    .post(`/${this.resource_remissions}`, this.document)
                    .then(response => {
                        if (response.data.success) {

                            this.remissionNewId = response.data.data.id
                            this.showDialogRemissionOptions = true;
                            this.$eventHub.$emit("reloadData");
                            this.resetDocument();
                            this.clickClose()
                            // this.getRecord()

                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data;
                        } else {
                            this.$message.error(error.response.data.message);
                        }
                    })
                    .then(() => {
                        this.loading_submit = false;
                    });
            },
            async assignDocument() {

                let q = this.form.quotation

                this.document.customer_id = q.customer_id
                this.document.currency_id = q.currency_id
                this.document.total_discount = q.total_discount
                this.document.total_tax = q.total_tax
                this.document.subtotal = q.subtotal
                this.document.total = q.total
                this.document.sale = q.sale
                this.document.items = q.items
                this.document.taxes = q.taxes

                await this.document.items.forEach((it) => {
                    it.id = null
                    // it.price = it.unit_price
                })

                this.document.quotation_id = this.form.id
            },

            async create() {
                await this.getTables()
                await this.getRecord()
            },
            getTables(){

                this.$http.get(`/${this.resource}/option/tables`).then(response => {
                    this.payment_methods = response.data.payment_methods
                    this.payment_forms = response.data.payment_forms
                })
            },
            async getRecord() {

                await this.$http
                    .get(`/${this.resource}/record2/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = `Cotización registrada: ` + this.form.identifier;
                    });

            },  
            clickFinalize() {
                location.href = `/${this.resource}`;
            }, 
            clickClose() {
                this.$emit("update:showDialog", false)
                this.initForm()
                this.resetDocument()
            }
        }

    }
</script>
