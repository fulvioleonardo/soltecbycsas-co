<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre  <span class="text-danger">*</span></label>
                            <el-input v-model="form.name" ></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Código Interno
                                <el-tooltip class="item" effect="dark" content="Código interno de la empresa para el control de sus productos" placement="top-start">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model="form.code" dusk="code"></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_unit_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.type_unit_id" dusk="type_unit_id" filterable>
                                <el-option v-for="option in type_units" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_unit_id" v-text="errors.type_unit_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.price}">
                            <label class="control-label">Precio Unitario (Venta) <span class="text-danger">*</span></label>
                            <el-input v-model="form.price" ></el-input>
                            <small class="form-control-feedback" v-if="errors.price" v-text="errors.price[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Impuesto</label>
                            <el-select v-model="form.tax_id" filterable >
                                <el-option v-for="option in taxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
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
        props: ['showDialog', 'recordId', 'external'],
        // components: {LotsForm},

        data() {
            return {
                showDialogLots:false,
                loading_submit: false,
                titleDialog: null,
                resource: 'co-items',
                errors: {},
                headers: headers_token,
                form: {},
                configuration: {},
                unit_types: [],
                taxes: [],
                type_units: [],
                affectation_igv_types: [],
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.type_units = response.data.typeUnits
                    this.taxes = response.data.taxes
                    this.form.tax_id = (this.taxes.length > 0)?this.taxes[0].id:null
                })


            this.$eventHub.$on('reloadTables', ()=>{
                this.reloadTables()
            })

            // await this.setDefaultConfiguration()

        },

        methods: {
            async reloadTables(){

                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.type_units = response.data.typeUnits
                        this.taxes = response.data.taxes
                        this.form.tax_id = (this.taxes.length > 0)?this.taxes[0].id:null
                    })

            },
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
                    id: null,
                    name: null,
                    code: null,
                    type_unit_id: 10,
                    description: null,
                    price: 0,
                    tax_id: null,
                }
            },
            resetForm() {
                this.initForm()
                // this.form.tax_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.setDefaultConfiguration()
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar Producto':'Nuevo Producto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                            // this.has_percentage_perception = (this.form.percentage_perception) ? true : false
                            // this.changeAffectationIgvType()
                        })
                }

            },
            loadRecord(){
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.changeAffectationIgvType()
                        })
                }
            },
            async submit() {

                this.loading_submit = true
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
