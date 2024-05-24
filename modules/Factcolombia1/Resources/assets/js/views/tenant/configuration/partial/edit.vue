<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div v-if="form.id" class="form-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.resolution_number}">
                            <label class="control-label">Número de resolución<span class="text-danger">*</span></label>
                            <el-input v-model="form.resolution_number" :disabled="true"></el-input>
                            <small class="form-control-feedback" v-if="errors.resolution_number" v-text="errors.resolution_number[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.resolution_date}">
                            <label class="control-label">Fecha resolución<span class="text-danger">*</span></label>
                           <el-date-picker
                            value-format="yyyy-MM-dd"
                            format="yyyy-MM-dd"
                            v-model="form.resolution_date"
                            type="date"
                            placeholder="Pick a day">
                            </el-date-picker>
                            <small class="form-control-feedback" v-if="errors.resolution_date" v-text="errors.resolution_date[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.resolution_date_end}">
                            <label class="control-label">Fecha resolución hasta<span class="text-danger">*</span></label>
                           <el-date-picker
                            value-format="yyyy-MM-dd"
                            format="yyyy-MM-dd"
                            v-model="form.resolution_date_end"
                            type="date"
                            placeholder="Pick a day">
                            </el-date-picker>
                            <small class="form-control-feedback" v-if="errors.resolution_date_end" v-text="errors.resolution_date_end[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.technical_key}">
                            <label class="control-label">Clave técnica<span class="text-danger">*</span></label>
                            <el-input v-model="form.technical_key" ></el-input>
                            <small class="form-control-feedback" v-if="errors.technical_key" v-text="errors.technical_key[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre<span class="text-danger">*</span></label>
                            <el-input v-model="form.name" :disabled="true"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.prefix}">
                            <label class="control-label">Prefijo<span class="text-danger">*</span></label>
                            <el-input v-model="form.prefix" :disabled="true"></el-input>
                            <small class="form-control-feedback" v-if="errors.prefix" v-text="errors.prefix[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.from}">
                            <label class="control-label">Desde<span class="text-danger">*</span></label>
                            <el-input v-model="form.from" ></el-input>
                            <small class="form-control-feedback" v-if="errors.from" v-text="errors.from[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.to}">
                            <label class="control-label">Hasta<span class="text-danger">*</span></label>
                            <el-input v-model="form.to" ></el-input>
                            <small class="form-control-feedback" v-if="errors.to" v-text="errors.to[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.generated}">
                            <label class="control-label">Generadas<span class="text-danger">*</span></label>
                            <el-input v-model="form.generated" ></el-input>
                            <small class="form-control-feedback" v-if="errors.generated" v-text="errors.generated[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripcion<span class="text-danger">*</span></label>
                            <el-input v-model="form.description" ></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
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

    export default {
        props: ['showDialog', 'record', 'external'],
        // components: {LotsForm},

        data() {
            return {
                showDialogLots:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'configuration',
                errors: {},
                headers: headers_token,
                form: {},
                form_clone: {},
                configuration: {},
                unit_types: [],
                taxes: [],
                type_units: [],
                affectation_igv_types: [],
            }
        },

        async created() {
            //await this.initForm()
            /*await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.type_units = response.data.typeUnits
                    this.taxes = response.data.taxes
                    this.form.tax_id = (this.taxes.length > 0)?this.taxes[0].id:null
                })


            this.$eventHub.$on('reloadTables', ()=>{
                this.reloadTables()
            })*/

            // await this.setDefaultConfiguration()
        },

        methods: {
            /*async reloadTables(){

                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.type_units = response.data.typeUnits
                        this.taxes = response.data.taxes
                        this.form.tax_id = (this.taxes.length > 0)?this.taxes[0].id:null
                    })

            },*/
            changeLotsEnabled(){

                // if(!this.form.lots_enabled){
                //     this.form.lot_code = null
                //     this.form.lots = []
                // }

            },
            addRowLot(lots){
                this.form.lots = lots
            },
            clickLotcode(){
                // if(this.form.stock <= 0)
                //     return this.$message.error('El stock debe ser mayor a 0')

                this.showDialogLots = true
            },
            changeHaveAccount(){
                if(!this.have_account) this.form.account_id = null
            },
            changeEnabledPercentageOfProfit(){
                // if(!this.enabled_percentage_of_profit) this.form.percentage_of_profit = 0
            },
            initForm() {
                this.loading_submit = false,
                this.errors = {}
                this.form = {

                }
            },
            resetForm() {
                this.initForm()
                // this.form.tax_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.setDefaultConfiguration()
            },
            create() {
                this.titleDialog =  'Editar'
                this.form = this.record
                /*if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                        })
                }*/

            },
            /*çloadRecord(){
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.changeAffectationIgvType()
                        })
                }
            },*/
            async submit() {
                this.loading_submit = true
                this.form_clone = this.form
                this.form_clone.date_from = this.form.resolution_date
                this.form_clone.date_to = this.form.resolution_date_end
                this.form_clone.resolution = this.form.resolution_number
                this.form_clone.type_document_id = this.form.code
                this.form_clone.description = this.form.description
                await this.$http.post(`/${this.resource}/type_document/${this.form.id}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$emit('refresh')
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

                await this.$http.post(`/client/configuration/storeServiceCompanieResolution`, this.form_clone)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getRecords()
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
                        this.loadingResolution = false
                        this.initForm()
                    })
            },

            close() {
                this.$emit('update:showDialog', false)
                this.$emit('refresh')
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
