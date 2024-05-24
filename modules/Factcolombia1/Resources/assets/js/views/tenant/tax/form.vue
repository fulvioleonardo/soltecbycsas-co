<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre *</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Codigo *</label>
                            <el-input v-model="form.code"></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.rate}">
                            <label class="control-label">Tasa *</label>
                            <el-input v-model="form.rate"></el-input>
                            <small class="form-control-feedback" v-if="errors.rate" v-text="errors.rate[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.conversion}">
                            <label class="control-label">Conversion *</label>
                            <el-input v-model="form.conversion"></el-input>
                            <small class="form-control-feedback" v-if="errors.conversion" v-text="errors.conversion[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.is_percentage}">
                            <label class="control-label">Tasa porcentaje *</label>
                            <div>
                                <el-switch
                                    v-model="form.is_percentage"
                                    active-text="Si"
                                    inactive-text="No"
                                    @change="validateRate(form.is_percentage)">
                                </el-switch>
                                <small class="form-control-feedback" v-if="errors.is_percentage" v-text="errors.is_percentage[0]"></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.is_fixed_value}">
                            <label class="control-label">Tasa valor fijo *</label>
                            <div>
                                <el-switch
                                    v-model="form.is_fixed_value"
                                    active-text="Si"
                                    inactive-text="No"
                                    @change="validateRate(form.is_fixed_value, false)">
                                </el-switch>
                                <small class="form-control-feedback" v-if="errors.is_fixed_value" v-text="errors.is_fixed_value[0]"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.is_retention}">
                            <label class="control-label">Impuesto de retención *</label>
                            <div>
                                <el-switch
                                    v-model="form.is_retention"
                                    active-text="Si"
                                    inactive-text="No">
                                </el-switch>
                                <small class="form-control-feedback" v-if="errors.is_retention" v-text="errors.is_retention[0]"></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.in_base}">
                            <label class="control-label">Retención en base *</label>
                            <div>
                                <el-switch
                                    v-model="form.in_base"
                                    active-text="Si"
                                    inactive-text="No">
                                </el-switch>
                                <small class="form-control-feedback" v-if="errors.in_base" v-text="errors.in_base[0]"></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.in_tax}">
                            <label class="control-label">Retención en impuesto</label>
                            <el-select v-model="form.in_tax" filterable :disabled="in_tax_retention_disabled()">
                                <el-option v-for="option in taxes_in_tax" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.in_tax" v-text="errors.in_tax[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.type_tax_id}">
                            <label class="control-label">Tipo impuesto</label>
                            <el-select v-model="form.type_tax_id" filterable >
                                <el-option v-for="option in type_taxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_tax_id" v-text="errors.type_tax_id[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" >Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    // import PercentagePerception from './partials/percentage_perception.vue'
    // import LotsForm from './partials/lots.vue'
    //import Helper from '../../../mixins/Helper';

    export default {
        props: ['showDialog', 'recordId', 'external'],
        // components: {LotsForm},
        //mixins: [Helper],

        data() {
            return {
                showDialogLots:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'co-taxes',
                errors: {},
                headers: headers_token,
                form: {
                },
                configuration: {},
                type_taxes: [],
                taxes_in_tax: [],
            }
        },

        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.type_taxes = response.data.type_taxes
                    this.taxes_in_tax = response.data.taxes_in_tax
//                    this.type_identity_documents = response.data.typeIdentityDocuments
//                    this.countries = response.data.countries
                })
        },

        computed: {
            urlData() {
                return (this.form.id) ? {method: 'put', url: `/${this.form.id}`} : {method: 'post', url: '/'};
            }
        },

        methods: {
            initForm() {
                this.loading_submit = false;
                this.errors = {
                };
                this.form = {
                    id: null,
                    name: 'PRUEBA',
                    code: '07',
                    rate: 10,
                    conversion: 100,
                    is_percentage: true,
                    is_fixed_value: false,
                    is_retention: false,
                    in_base: false,
                    in_tax: null,
                    type_tax_id: null,
                }
            },

            departmentss(edit = false) {
                if (!edit) {
                    // console.log("s")
                    this.form.department_id = null;
                    this.form.city_id = null;
                    this.departments = [];
                    this.cities = [];
                }
                if (this.form.country_id != null) this.getDepartment(this.form.country_id).then(rows => this.departments = rows);
            },

            citiess(edit = false) {
                if (!edit) {
                    this.form.city_id = null;
                    this.cities = [];
                }

                if (this.form.department_id != null) this.getCities(this.form.department_id).then(rows => this.cities = rows);
            },
            resetForm() {
                this.initForm()
            },

            create() {
                this.titleDialog = (this.recordId)? 'Editar Impuesto':'Nuevo Impuesto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data

                            this.departmentss(true);
                            this.citiess(true);
                        })
                }
            },

            async submit() {
                this.loading_submit = true
                if(this.form.id){
                    await this.$http.put(`/${this.resource}/${this.form.id}`, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message)
                                if (this.external) {
                                    this.$eventHub.$emit('reloadDataItems', response.data.id)
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
                }else{
                    await this.$http.post(`/${this.resource}`, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message)
                                if (this.external) {
                                    this.$eventHub.$emit('reloadDataItems', response.data.id)
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
                }
            },
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            },
            changeHasIsc() {
                this.form.system_isc_type_id = null
                this.form.percentage_isc = 0
                this.form.suggested_price = 0
            },

            validateRate(val, isPercentage = true) {
                if (isPercentage)
                    this.form.is_fixed_value = !val;
                if (!isPercentage)
                    this.form.is_percentage = !val;
            },

            in_tax_retention_disabled() {
                if (this.form.is_retention && !this.form.in_base)
                    return false;
                else
                    return true;
            }
        }
    }
</script>
