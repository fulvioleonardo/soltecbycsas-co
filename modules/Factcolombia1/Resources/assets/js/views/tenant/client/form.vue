<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_person_id}">
                            <label class="control-label">Tipo de persona</label>
                            <el-select v-model="form.type_person_id" dusk="type_person_id" filterable>
                                <el-option v-for="option in type_persons" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_person_id" v-text="errors.type_person_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_regime_id}">
                            <label class="control-label">Tipo de régimen</label>
                            <el-select v-model="form.type_regime_id" dusk="type_regime_id" filterable>
                                <el-option v-for="option in type_regimes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_regime_id" v-text="errors.type_regime_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_identity_document_id}">
                            <label class="control-label">Tipo de identificación</label>
                            <el-select v-model="form.type_identity_document_id" dusk="type_identity_document_id" filterable>
                                <el-option v-for="option in type_identity_documents" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_identity_document_id" v-text="errors.type_identity_document_id[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.dv}">
                            <label class="control-label">Dv  </label>
                            <el-input v-model="form.dv" ></el-input>
                            <small class="form-control-feedback" v-if="errors.dv" v-text="errors.dv[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.identification_number}">
                            <label class="control-label">N° Identificación  </label>
                            <el-input v-model="form.identification_number" ></el-input>
                            <small class="form-control-feedback" v-if="errors.identification_number" v-text="errors.identification_number[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre  </label>
                            <el-input v-model="form.name" ></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.country_id}">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id" filterable @change="departmentss()">
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.department_id}">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id" filterable @change="citiess()">
                                <el-option v-for="option in departments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.city_id}">
                            <label class="control-label">Ciudad</label>
                            <el-select v-model="form.city_id" filterable >
                                <el-option v-for="option in cities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.city_id" v-text="errors.city_id[0]"></small>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Dirección  </label>
                            <el-input v-model="form.address" ></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.phone}">
                            <label class="control-label">Teléfono  </label>
                            <el-input v-model="form.phone" ></el-input>
                            <small class="form-control-feedback" v-if="errors.phone" v-text="errors.phone[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo electrónico  </label>
                            <el-input v-model="form.email" ></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Código interno  </label>
                            <el-input v-model="form.code" ></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div>
                </div>


            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
        <!-- <percentage-perception
                :showDialog.sync="showPercentagePerception"
                :percentage_perception="percentage_perception">
        </percentage-perception> -->

        <!-- <lots-form
            :showDialog.sync="showDialogLots"
            :stock="form.stock"
            :recordId="recordId"
            :lots="form.lots"
            @addRowLot="addRowLot">
        </lots-form> -->

    </el-dialog>
</template>

<script>
    // import PercentagePerception from './partials/percentage_perception.vue'
    // import LotsForm from './partials/lots.vue'
    import Helper from '../../../mixins/Helper';

    export default {
        props: ['showDialog', 'recordId', 'external'],
        // components: {LotsForm},
        mixins: [Helper],

        data() {
            return {
                showDialogLots:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'co-clients',
                errors: {},
                headers: headers_token,
                form: {},
                configuration: {},
                type_persons: [],
                type_regimes: [],
                type_identity_documents: [],
                countries: [],
                departments: [],
                cities: [],
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.type_persons = response.data.typePeople
                    this.type_regimes = response.data.typeRegimes
                    this.type_identity_documents = response.data.typeIdentityDocuments
                    this.countries = response.data.countries
                })


            // await this.setDefaultConfiguration()

        },

        computed: {
            urlData() {
                return (this.form.id) ? {method: 'put', url: `/${this.form.id}`} : {method: 'post', url: '/'};
            }
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                this.errors = {}
                this.form = {
                    id: null,
                    type_person_id: null,
                    type_regime_id: null,
                    type_identity_document_id: null,
                    identification_number: null,
                    name: null,
                    country_id: null,
                    department_id: null,
                    city_id: null,
                    address: null,
                    phone: null,
                    email: null,
                    code: null,
                    dv: null,
                }

                this.departmentss();
                this.citiess();
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
                // this.form.tax_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.setDefaultConfiguration()
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data

                            this.departmentss(true);
                            this.citiess(true);
                            // this.has_percentage_perception = (this.form.percentage_perception) ? true : false
                            // this.changeAffectationIgvType()
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
        }
    }
</script>
