<template>
    <el-dialog width="60%" :title="titleDialog" :visible="showDialog" @close="close" @open="create" @opened="opened" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">

                
                <el-tabs v-model="activeName">
                    <el-tab-pane label="Información general" name="general">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.code}">
                                    <label class="control-label">Código</label>
                                    <el-input v-model="form.code">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.payroll_type_document_identification_id}">
                                    <label class="control-label">Tipo de identificación</label>
                                    <el-select v-model="form.payroll_type_document_identification_id"  filterable>
                                        <el-option v-for="option in payroll_type_document_identifications" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payroll_type_document_identification_id" v-text="errors.payroll_type_document_identification_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.identification_number}">
                                    <label class="control-label">N° Identificación  </label>
                                    <el-input v-model="form.identification_number">
                                        <!-- <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="changeNumberIdentification">
                                        </el-button> -->
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.identification_number" v-text="errors.identification_number[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.first_name}">
                                    <label class="control-label">Nombre  </label>
                                    <el-input v-model="form.first_name" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.first_name" v-text="errors.first_name[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.surname}">
                                    <label class="control-label">Primer Apellido  </label>
                                    <el-input v-model="form.surname" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.surname" v-text="errors.surname[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.second_surname}">
                                    <label class="control-label">Segundo Apellido  </label>
                                    <el-input v-model="form.second_surname" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.second_surname" v-text="errors.second_surname[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.cellphone}">
                                    <label class="control-label">Celular</label>
                                    <el-input v-model="form.cellphone" :maxlength="11"></el-input>
                                    <small class="form-control-feedback" v-if="errors.cellphone" v-text="errors.cellphone[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.email}">
                                    <label class="control-label">Correo electrónico</label>
                                    <el-input v-model="form.email" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.municipality_id}">
                                    <label class="control-label">Municipalidad</label>
                                    <el-select v-model="form.municipality_id"  filterable>
                                        <el-option v-for="option in municipalities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.municipality_id" v-text="errors.municipality_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group" :class="{'has-danger': errors.address}">
                                    <label class="control-label">Dirección</label>
                                    <el-input v-model="form.address" dusk="address"></el-input>
                                    <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                                </div>
                            </div>
                        </div>

                    </el-tab-pane>
                    
                    <el-tab-pane label="Información laboral" name="working">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.type_worker_id}">
                                    <label class="control-label">Tipo de empleado</label>
                                    <el-select v-model="form.type_worker_id" filterable>
                                        <el-option v-for="option in type_workers" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_worker_id" v-text="errors.type_worker_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.sub_type_worker_id}">
                                    <label class="control-label">Subtipo de empleado</label>
                                    <el-select v-model="form.sub_type_worker_id" filterable>
                                        <el-option v-for="option in sub_type_workers" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.sub_type_worker_id" v-text="errors.sub_type_worker_id[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.type_contract_id}">
                                    <label class="control-label">Tipo contrato</label>
                                    <el-select v-model="form.type_contract_id" filterable>
                                        <el-option v-for="option in type_contracts" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_contract_id" v-text="errors.type_contract_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.salary}">
                                    <label class="control-label">Salario</label>
                                    <el-input v-model="form.salary"></el-input>
                                    <small class="form-control-feedback" v-if="errors.salary" v-text="errors.salary[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.payroll_period_id}">
                                    <label class="control-label">Frecuencia de pago</label>
                                    <el-select v-model="form.payroll_period_id" filterable>
                                        <el-option v-for="option in payroll_periods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payroll_period_id" v-text="errors.payroll_period_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.position}">
                                    <label class="control-label">Cargo</label>
                                    <el-input v-model="form.position"></el-input>
                                    <small class="form-control-feedback" v-if="errors.position" v-text="errors.position[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.work_start_date}">
                                    <label class="control-label">Fecha inicio de labores</label>
                                    <el-date-picker v-model="form.work_start_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.work_start_date" v-text="errors.work_start_date[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-3 mt-4">
                                <div class="form-group" :class="{'has-danger': errors.high_risk_pension}">
                                    <el-checkbox v-model="form.high_risk_pension">Pensión de alto riesgo</el-checkbox>
                                </div>
                            </div>

                            <div class="col-md-3 mt-4">
                                <div class="form-group" :class="{'has-danger': errors.integral_salarary}">
                                    <el-checkbox v-model="form.integral_salarary">Salario integral</el-checkbox>
                                </div>
                            </div>
                        </div>

                    </el-tab-pane>
                    
                    <el-tab-pane label="Método de pago" name="payment_method">
                
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors['payment.payment_method_id']}">
                                    <label class="control-label">Métodos de pago<span class="text-danger"> *</span></label>
                                    <el-select v-model="form.payment.payment_method_id"   filterable @change="changePaymentMethod">
                                        <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors['payment.payment_method_id']" v-text="errors['payment.payment_method_id'][0]"></small>
                                </div>
                            </div>
                                
                            <template v-if="show_inputs_payment_method">
                                <div class="col-md-3">
                                    <div class="form-group" :class="{'has-danger': errors['payment.bank_name']}">
                                        <label class="control-label">Nombre del banco</label>
                                        <el-input v-model="form.payment.bank_name"></el-input>
                                        <small class="form-control-feedback" v-if="errors['payment.bank_name']" v-text="errors['payment.bank_name'][0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" :class="{'has-danger': errors['payment.account_type']}">
                                        <label class="control-label">Tipo de cuenta</label>
                                        <el-input v-model="form.payment.account_type"></el-input>
                                        <small class="form-control-feedback" v-if="errors['payment.account_type']" v-text="errors['payment.account_type'][0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" :class="{'has-danger': errors['payment.account_number']}">
                                        <label class="control-label">Número de cuenta</label>
                                        <el-input v-model="form.payment.account_number"></el-input>
                                        <small class="form-control-feedback" v-if="errors['payment.account_number']" v-text="errors['payment.account_number'][0]"></small>
                                    </div>
                                </div>
                            </template>

                        </div>

                    </el-tab-pane>

                </el-tabs>


                <div class="row">

 
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'external'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'payroll/workers',
                errors: {},
                form: {},
                type_workers: [],
                sub_type_workers: [],
                payroll_type_document_identifications: [],
                type_contracts: [],
                payroll_periods: [],
                municipalities: [], 
                payment_methods: [], 
                loading_search: false,
                show_inputs_payment_method: true,
                activeName: 'general',
            }
        },
        async created() {
            await this.initForm()
            await this.getTables()

        },
        computed: { 
        },
        methods: {
            async getTables(){

                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => { 

                        this.type_workers = response.data.type_workers 
                        this.sub_type_workers = response.data.sub_type_workers 
                        this.payroll_type_document_identifications = response.data.payroll_type_document_identifications 
                        this.type_contracts = response.data.type_contracts 
                        this.municipalities = response.data.municipalities 
                        this.payroll_periods = response.data.payroll_periods 
                        this.payment_methods = response.data.payment_methods 

                        this.form.type_worker_id = this.type_workers.length > 0 ? this.type_workers[0].id : null
                        this.form.sub_type_worker_id = this.sub_type_workers.length > 0 ? this.sub_type_workers[0].id : null
                        this.form.type_contract_id = this.type_contracts.length > 0 ? this.type_contracts[0].id : null

                    })
            },
            changePaymentMethod(){

                //mostrar campos adicionales, si el metodo de pago es el definido en el arreglo/obligatorio
                this.show_inputs_payment_method = [2,3,4,5,6,7,21,22,30,31,42,45,46,47].includes(this.form.payment.payment_method_id)

            },
            initForm() {

                this.errors = {}

                this.form = {
                    id: null,
                    code: null,
                    type_worker_id: null,
                    sub_type_worker_id: null,
                    payroll_type_document_identification_id: null,
                    municipality_id: null,
                    type_contract_id: null,
                    identification_number: null,
                    surname: null,
                    second_surname: null,
                    first_name: null,
                    address: null,
                    high_risk_pension: false,
                    integral_salarary: false,
                    salary: null,

                    email: null,
                    cellphone: null,
                    position: null,
                    work_start_date: moment().format('YYYY-MM-DD'),
                    payroll_period_id: null,
                    payment: {
                        payment_method_id: null,
                        bank_name: null,
                        account_type: null,
                        account_number: null,
                    }, 
                }

            }, 
            async opened() {

            },
            create() {

                this.titleDialog = this.recordId ? 'Editar empleado' : 'Nuevo empleado'
                this.getRecord()
            }, 
            getRecord(){

                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.changePaymentMethod()
                        })
                
                }else{

                    this.setInitialData()
                }
            },
            setInitialData(){

                const payroll_month = _.find(this.payroll_periods, {id:5})
                this.form.payroll_period_id = payroll_month ? payroll_month.id : null
                 
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataWorkers', response.data.id)
                            } else {
                                this.$eventHub.$emit('reloadData')
                            }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            }, 
            close() {
                this.$eventHub.$emit('initInputPerson')
                this.$emit('update:showDialog', false)
                this.initForm()
            }, 
        }
    }
</script>
