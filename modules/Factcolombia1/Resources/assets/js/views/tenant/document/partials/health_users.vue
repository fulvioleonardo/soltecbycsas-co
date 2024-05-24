<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="clickClose" width="60%" :close-on-click-modal="false" :show-close="false" append-to-body>
        <div class="row mt-3">
            <div class="col-lg-4 col-md-4">
                <div class="form-group" :class="{'has-danger': errors.provider_code}">
                    <label class="control-label">Codigo del proveedor</label>
                    <el-input v-model="form.provider_code" :maxlength=15></el-input>
                    <small class="form-control-feedback" v-if="errors.provider_code" v-text="errors.provider_code[0]"></small>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="form-group" :class="{'has-danger': errors.health_type_document_identification_id}">
                    <label class="control-label">Tipo documento de identificacion</label>
                    <el-select v-model="form.health_type_document_identification_id" filterable>
                        <el-option v-for="option in health_type_document_identifications" :key="option.id" :value="option.id" :label="`${option.name} - ${option.code}`"></el-option>
                    </el-select>
                    <small class="form-control-feedback" v-if="errors.health_type_document_identification_id" v-text="errors.health_type_document_identification_id[0]"></small>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="form-group" :class="{'has-danger': errors.identification_number}">
                    <label class="control-label">Numero identificacion</label>
                    <el-input v-model="form.identification_number" :minlength=4 :maxlength=15></el-input>
                    <small class="form-control-feedback" v-if="errors.identification_number" v-text="errors.identification_number[0]"></small>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.first_name}">
                    <label class="control-label">Primer nombre</label>
                    <el-input v-model="form.first_name" :minlength=3 :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.first_name" v-text="errors.first_name[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.middle_name}">
                    <label class="control-label">Segundo nombre</label>
                    <el-input v-model="form.middle_name" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.middle_name" v-text="errors.middle_name[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.surname}">
                    <label class="control-label">Primer apellido</label>
                    <el-input v-model="form.surname" :minlength=3 :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.surname" v-text="errors.surname[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.second_surname}">
                    <label class="control-label">Segundo apellido</label>
                    <el-input v-model="form.second_surname" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.second_surname" v-text="errors.second_surname[0]"></small>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.health_type_user_id}">
                    <label class="control-label">Tipo usuario del sector salud</label>
                    <el-select v-model="form.health_type_user_id" filterable>
                        <el-option v-for="option in health_type_users" :key="option.id" :value="option.id" :label="option.name"></el-option>
                    </el-select>
                    <small class="form-control-feedback" v-if="errors.health_type_user_id" v-text="errors.health_type_user_id[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.health_contracting_payment_method_id}">
                    <label class="control-label">Metodo de pago del contrato</label>
                    <el-select v-model="form.health_contracting_payment_method_id" filterable>
                        <el-option v-for="option in health_contracting_payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                    </el-select>
                    <small class="form-control-feedback" v-if="errors.health_contracting_payment_method_id" v-text="errors.health_contracting_payment_method_id[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.health_coverage_id}">
                    <label class="control-label">Tipo de cobertura</label>
                    <el-select v-model="form.health_coverage_id" filterable>
                        <el-option v-for="option in health_coverages" :key="option.id" :value="option.id" :label="option.name"></el-option>
                    </el-select>
                    <small class="form-control-feedback" v-if="errors.health_coverage_id" v-text="errors.health_coverage_id[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.autorization_numbers}">
                    <label class="control-label">Numeros de autorizacion</label>
                    <el-input v-model="form.autorization_numbers" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.autorization_numbers" v-text="errors.autorization_numbers[0]"></small>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.mipres}">
                    <label class="control-label">Mipres</label>
                    <el-input v-model="form.mipres" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.mipres" v-text="errors.mipres[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.mipres_delivery}">
                    <label class="control-label">Entrega mipres</label>
                    <el-input v-model="form.mipres_delivery" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.mipres_delivery" v-text="errors.mipres_delivery[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.contract_number}">
                    <label class="control-label">Numero de contrato</label>
                    <el-input v-model="form.contract_number" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.contract_number" v-text="errors.contract_number[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.policy_number}">
                    <label class="control-label">Numero de poliza</label>
                    <el-input v-model="form.policy_number" :maxlength=25></el-input>
                    <small class="form-control-feedback" v-if="errors.policy_number" v-text="errors.policy_number[0]"></small>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.co_payment}">
                    <label class="control-label">Copago</label>
                    <el-input v-model="form.co_payment" :maxlength=12></el-input>
                    <small class="form-control-feedback" v-if="errors.co_payment" v-text="errors.co_payment[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.moderating_fee}">
                    <label class="control-label">Cuota moderadora</label>
                    <el-input v-model="form.moderating_fee" :maxlength=12></el-input>
                    <small class="form-control-feedback" v-if="errors.moderating_fee" v-text="errors.moderating_fee[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.recovery_fee}">
                    <label class="control-label">Cuota de recuperacion</label>
                    <el-input v-model="form.recovery_fee" :maxlength=12></el-input>
                    <small class="form-control-feedback" v-if="errors.recovery_fee" v-text="errors.recovery_fee[0]"></small>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group" :class="{'has-danger': errors.shared_payment}">
                    <label class="control-label">Pago compartido</label>
                    <el-input v-model="form.shared_payment" :maxlength=12></el-input>
                    <small class="form-control-feedback" v-if="errors.shared_payment" v-text="errors.shared_payment[0]"></small>
                </div>
            </div>
        </div>

        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="clickClose()">Cerrar</el-button>
            <el-button class="add" type="primary" @click="clickSave">{{titleAction}}</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordItemHealthUser'],

        data() {
            return {
                titleDialog: '',
                titleAction: '',
                loading: false,
                resource: 'co-documents',
                errors: {},
                form: {},
                company: {},
                locked_emission:{},
                health_type_document_identifications: [],
                health_type_users: [],
                health_contracting_payment_methods: [],
                health_coverages: [],
            }
        },

        watch: {
            'form.provider_code'(newVal) {
                if(this.form.provider_code)
                    this.form.provider_code = newVal.toUpperCase();
            },

            'form.first_name'(newVal) {
                if(this.form.first_name)
                    this.form.first_name = newVal.toUpperCase();
            },

            'form.middle_name'(newVal) {
                if(this.form.middle_name)
                    this.form.middle_name = newVal.toUpperCase();
            },

            'form.surname'(newVal) {
                if(this.form.surname)
                    this.form.surname = newVal.toUpperCase();
            },

            'form.second_surname'(newVal) {
                if(this.form.second_surname)
                    this.form.second_surname = newVal.toUpperCase();
            },

            'form.autorization_numbers'(newVal) {
                if(this.form.autorization_numbers)
                    this.form.autorization_numbers = newVal.toUpperCase();
            },

            'form.mipres'(newVal) {
                if(this.form.mipres)
                    this.form.mipres = newVal.toUpperCase();
            },

            'form.mipres_delivery'(newVal) {
                if(this.form.mipres_delivery)
                    this.form.mipres_delivery = newVal.toUpperCase();
            },

            'form.contract_number'(newVal) {
                if(this.form.contract_number)
                    this.form.contract_number = newVal.toUpperCase();
            },

            'form.policy_number'(newVal) {
                if(this.form.policy_number)
                    this.form.policy_number = newVal.toUpperCase();
            }
        },

        async created() {
            this.initForm()
        },

        methods: {
            async create() {
                this.titleDialog = (this.recordItemHealthUser) ? ' Editar usuario para factura del sector salud' : ' Agregar usuario para factura del sector salud';
                this.titleAction = (this.recordItemHealthUser) ? ' Editar' : ' Agregar';
                if (this.recordItemHealthUser) {
                    this.form.provider_code = this.recordItemHealthUser.provider_code
                    this.form.health_type_document_identification_id = this.recordItemHealthUser.health_type_document_identification_id
                    this.form.identification_number = this.recordItemHealthUser.identification_number
                    this.form.surname = this.recordItemHealthUser.surname
                    this.form.second_surname = this.recordItemHealthUser.second_surname
                    this.form.first_name = this.recordItemHealthUser.first_name
                    this.form.middle_name = this.recordItemHealthUser.middle_name
                    this.form.health_type_user_id = this.recordItemHealthUser.health_type_user_id
                    this.form.health_contracting_payment_method_id = this.recordItemHealthUser.health_contracting_payment_method_id
                    this.form.health_coverage_id = this.recordItemHealthUser.health_coverage_id
                    this.form.autorization_numbers = this.recordItemHealthUser.autorization_numbers
                    this.form.mipres = this.recordItemHealthUser.mipres
                    this.form.mipres_delivery = this.recordItemHealthUser.mipres_delivery
                    this.form.contract_number = this.recordItemHealthUser.contract_number
                    this.form.policy_number = this.recordItemHealthUser.policy_number
                    this.form.co_payment = this.recordItemHealthUser.co_payment
                    this.form.moderating_fee = this.recordItemHealthUser.moderating_fee
                    this.form.recovery_fee = this.recordItemHealthUser.recovery_fee
                    this.form.shared_payment = this.recordItemHealthUser.shared_payment
                }
                else
                  this.initForm()
            },

            initForm() {
                this.health_type_document_identifications = []
                this.health_type_users = []
                this.health_contracting_payment_methods = []
                this.health_coverages = []
                this.errors = {}
                this.form = {
                    provider_code: null,
                    health_type_document_identification_id: null,
                    identification_number: null,
                    surname: null,
                    second_surname: null,
                    first_name: null,
                    middle_name: null,
                    health_type_user_id: null,
                    health_contracting_payment_method_id: null,
                    health_coverage_id: null,
                    autorization_numbers: null,
                    mipres: null,
                    mipres_delivery: null,
                    contract_number: null,
                    policy_number: null,
                    co_payment: 0,
                    moderating_fee: 0,
                    recovery_fee: 0,
                    shared_payment: 0,
                }
                this.$http.get(`/${this.resource}/health/tables`).then(response => {
                    this.health_type_document_identifications = response.data.health_type_document_identifications;
                    this.health_type_users = response.data.health_type_users;
                    this.health_contracting_payment_methods = response.data.health_contracting_payment_methods;
                    this.health_coverages = response.data.health_coverages;
                })
            },

            validate(){
                let isValidText = false;
                const alpha_num_dash = /^[A-Za-z0-9-]*$/; // Letras numeros y guiones, opcional
                const num_only = /^[0-9]+$/; // Numeros solamente, obligatorio
                const alpha_only = /^[A-Za-zñÑ]*$/; // Letras solamente, opcional
                const alpha_num_dash_semicolon = /^[A-Za-z0-9-;]*$/; // Letras numeros y guiones, opcional
                const num_dot = /^[0-9.]+$/; // Numeros solamente, obligatorio
                const response = {}

                response.success = true

                isValidText = alpha_num_dash.test(this.form.provider_code);
                if(!isValidText){
                    response.success = false
                    response.provider_code = ["Solo se permiten letras, numeros y guiones en este campo"]
                }

                if(!this.form.health_type_document_identification_id)
                {
                    response.success = false
                    response.health_type_document_identification_id = ["Debe seleccionar un tipo de documento de id"]
                }

                if(!this.form.identification_number){
                    response.success = false
                    response.identification_number = ["Este campo es obligatorio"]
                }
                isValidText = num_only.test(this.form.identification_number);
                if(!isValidText){
                    response.success = false
                    response.identification_number = ["Solo se permiten numeros en este campo"]
                }

                if(!this.form.first_name){
                    response.success = false
                    response.first_name = ["Este campo es obligatorio"]
                }
                isValidText = alpha_only.test(this.form.first_name);
                if(!isValidText){
                    response.success = false
                    response.first_name = ["Solo se permiten letras en este campo"]
                }

                isValidText = alpha_only.test(this.form.middle_name);
                if(!isValidText){
                    response.success = false
                    response.middle_name = ["Solo se permiten letras en este campo"]
                }

                if(!this.form.surname){
                    response.success = false
                    response.surname = ["Este campo es obligatorio"]
                }
                isValidText = alpha_only.test(this.form.surname);
                if(!isValidText){
                    response.success = false
                    response.surname = ["Solo se permiten letras en este campo"]
                }

                isValidText = alpha_only.test(this.form.second_surname);
                if(!isValidText){
                    response.success = false
                    response.second_surname = ["Solo se permiten letras en este campo"]
                }

                if(!this.form.health_type_user_id)
                {
                    response.success = false
                    response.health_type_user_id = ["Debe seleccionar un tipo de usuario"]
                }

                if(!this.form.health_contracting_payment_method_id)
                {
                    response.success = false
                    response.health_contracting_payment_method_id = ["Debe seleccionar un tipo de metodo de pago de contrato"]
                }

                if(!this.form.health_coverage_id)
                {
                    response.success = false
                    response.health_coverage_id = ["Debe seleccionar un tipo de cobetura"]
                }

                isValidText = alpha_num_dash_semicolon.test(this.form.autorization_numbers);
                if(!isValidText){
                    response.success = false
                    response.autorization_numbers = ["Solo se permiten letras, numeros, guiones y punto y coma en este campo"]
                }

                isValidText = alpha_num_dash_semicolon.test(this.form.mipres);
                if(!isValidText){
                    response.success = false
                    response.mipres = ["Solo se permiten letras, numeros, guiones y punto y coma en este campo"]
                }

                isValidText = alpha_num_dash_semicolon.test(this.form.mipres_delivery);
                if(!isValidText){
                    response.success = false
                    response.mipres_delivery = ["Solo se permiten letras, numeros, guiones y punto y coma en este campo"]
                }

                isValidText = alpha_num_dash_semicolon.test(this.form.contract_number);
                if(!isValidText){
                    response.success = false
                    response.contract_number = ["Solo se permiten letras, numeros, guiones y punto y coma en este campo"]
                }

                isValidText = alpha_num_dash_semicolon.test(this.form.policy_number);
                if(!isValidText){
                    response.success = false
                    response.policy_number = ["Solo se permiten letras, numeros, guiones y punto y coma en este campo"]
                }

                isValidText = num_dot.test(this.form.co_payment);
                if(!isValidText){
                    response.success = false
                    response.co_payment = ["Solo se permiten numeros y punto decimal"]
                }

                isValidText = num_dot.test(this.form.moderating_fee);
                if(!isValidText){
                    response.success = false
                    response.moderating_fee = ["Solo se permiten numeros y punto decimal"]
                }

                isValidText = num_dot.test(this.form.recovery_fee);
                if(!isValidText){
                    response.success = false
                    response.recovery_fee = ["Solo se permiten numeros y punto decimal"]
                }

                isValidText = num_dot.test(this.form.shared_payment);
                if(!isValidText){
                    response.success = false
                    response.shared_payment = ["Solo se permiten numeros y punto decimal"]
                }

                return response
            },

            clickSave() {
                let validate = this.validate()
                if(!validate.success)
                    this.errors = validate
                else{
                    this.errors = {}
                    if (this.recordItemHealthUser){
                        this.form.indexi = this.recordItemHealthUser.indexi
                    }
                    this.$emit('add', this.form);
                    this.clickClose()
                }
            },

            clickClose() {
                this.initForm();
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
