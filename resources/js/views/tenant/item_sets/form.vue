<template>
    <el-dialog width="65%" :title="titleDialog" :visible="showDialog" :close-on-click-modal="false" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <!-- <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre<span class="text-danger">*</span></label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.second_name}">
                            <label class="control-label">Nombre secundario </label>
                            <el-input v-model="form.second_name" dusk="second_name"></el-input>
                            <small class="form-control-feedback" v-if="errors.second_name" v-text="errors.second_name[0]"></small>
                        </div>
                    </div>

                    
                     <!-- <div class="col-md-9">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre  <span class="text-danger">*</span></label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div> -->
                     <div class="col-md-9">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>


                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_type_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unit_type_id" dusk="unit_type_id" filterable>
                                <el-option v-for="option in unit_types" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unit_type_id" v-text="errors.unit_type_id[0]"></small>
                        </div>
                    </div>

                    
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.individual_items}">
                            <label class="control-label">Elegir productos</label>
                            <el-select v-model="form.individual_items" filterable multiple collapse-tags @change="changeIndividualItems" >
                                <el-option v-for="option in individual_items" :key="option.id" :value="option.id" :label="option.full_description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.individual_items" v-text="errors.individual_items[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id" filterable>
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.sale_unit_price}">
                            <label class="control-label">Precio Unitario (Venta) <span class="text-danger">*</span></label>
                            <el-input v-model="form.sale_unit_price" dusk="sale_unit_price" @input="calculatePercentageOfProfitBySale"></el-input>
                            <small class="form-control-feedback" v-if="errors.sale_unit_price" v-text="errors.sale_unit_price[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h5 class="separator-title">Campos adicionales</h5>
                    </div>
                    <div class="row col-md-12"> 
                        <div class="col-md-3">
                            <div class="form-group" >
                                <label class="control-label">Imágen <span class="text-danger"></span></label>
                                <el-upload class="avatar-uploader"
                                        :data="{'type': 'items'}"
                                        :headers="headers"
                                        :action="`/${resource}/upload`"
                                        :show-file-list="false"
                                        :on-success="onSuccess">
                                    <img v-if="form.image_url" :src="form.image_url" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </div> 
                        </div>
 
                        <div class="col-md-9"> 
                            <div class="row">

                                <div class="short-div col-md-8"> 
                                    <div class="form-group" :class="{'has-danger': errors.tax_id}">
                                        <label class="control-label">Impuesto (Venta)</label>
                                        <el-select v-model="form.tax_id" filterable>
                                            <el-option v-for="option in taxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                                    </div>
                                </div>
                                 
                                <div class="short-div col-md-4">
                                    
                                    <div class="form-group" :class="{'has-danger': errors.internal_id}">
                                        <label class="control-label">Código Interno
                                            <el-tooltip class="item" effect="dark" content="Código interno de la empresa para el control de sus productos" placement="top-start">
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                        </label>
                                        <el-input v-model="form.internal_id" dusk="internal_id"></el-input>
                                        <small class="form-control-feedback" v-if="errors.internal_id" v-text="errors.internal_id[0]"></small>
                                    </div>
                                </div> 

                            </div>
                        </div> 
                    </div> 


 
                </div>
            </div>
            <div class="form-actions text-right pt-2">
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
                warehouses: [],
                loading_submit: false,
                showPercentagePerception: false,
                has_percentage_perception: false,
                percentage_perception:null,
                enabled_percentage_of_profit:false,
                titleDialog: null,
                resource: 'item-sets',
                errors: {},                
                headers: headers_token,
                form: {},
                unit_types: [],
                currency_types: [],
                individual_items: [],
                system_isc_types: [],
                affectation_igv_types: [],
                accounts: [],
                show_has_igv:true,
                taxes: [],
                have_account:false,
                item_unit_type:{
                    id:null,
                    unit_type_id:null,
                    quantity_unit:0,
                    price1:0,
                    price2:0,
                    price3:0,
                    price_default:2,

                }
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.taxes = response.data.taxes
                    this.unit_types = response.data.unit_types
                    this.accounts = response.data.accounts
                    this.currency_types = response.data.currency_types
                    this.individual_items = response.data.individual_items
                    this.warehouses = response.data.warehouses

                    this.form.tax_id = (this.taxes.length > 0)?this.taxes[0].id:null
                })

            this.$eventHub.$on('submitPercentagePerception', (data)=>{
                this.form.percentage_perception = data
                if(!this.form.percentage_perception) this.has_percentage_perception = false
            })

        },
        methods: {
            changeIndividualItems(){
                // console.log(this.form.individual_items)
                let acum_sale_unit_price = 0

                this.form.individual_items.forEach(id => {
                    
                    let individual_item = _.find(this.individual_items,{'id':id})
                    acum_sale_unit_price += parseFloat(individual_item.sale_unit_price)
                });

                this.form.sale_unit_price = acum_sale_unit_price
                this.form.sale_unit_price_set = acum_sale_unit_price
            },
            initForm() {
                this.loading_submit = false,
                this.errors = {}
                this.form = {
                    id: null,
                    item_type_id: '01',
                    internal_id: null,
                    description: null,
                    name: null,
                    second_name: null,
                    unit_type_id: 10,
                    currency_type_id: 170,
                    sale_unit_price: 0,
                    purchase_unit_price: 0,
                    calculate_quantity: false,
                    stock: 0,
                    stock_min: 1,
                    has_perception: false,
                    item_unit_types:[],
                    percentage_of_profit: 0,
                    percentage_perception: 0,
                    image: null,
                    image_url: null,
                    temp_path: null,
                    account_id: null,
                    is_set: true,
                    sale_unit_price_set: 0,
                    date_of_due:null,
                    tax_id: 1,
                    purchase_tax_id: 1,
                    individual_items:[]
                }
                this.show_has_igv = true
                this.enabled_percentage_of_profit = false
            },
            onSuccess(response, file, fileList) {
                if (response.success) {
                    this.form.image = response.data.filename
                    this.form.image_url = response.data.temp_image
                    this.form.temp_path = response.data.temp_path
                } else {
                    this.$message.error(response.message)
                }
            },  
            changeAffectationIgvType(){

                // let affectation_igv_type_exonerated = [20,21,30,31,32,33,34,35,36,37]
                // let is_exonerated = affectation_igv_type_exonerated.includes((parseInt(this.form.sale_affectation_igv_type_id)));

                // if(is_exonerated){
                //     this.show_has_igv = false
                //     this.form.has_igv = true
                // }else{
                //     this.show_has_igv = true
                // }

            },
            resetForm() {
                this.initForm()
                // this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                // this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar producto compuesto':'Nuevo producto compuesto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data 
                            this.changeAffectationIgvType()
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
            
            changeHaveAccount(){
                if(!this.have_account) this.form.account_id = null
            },
            changeEnabledPercentageOfProfit(){
                // if(!this.enabled_percentage_of_profit) this.form.percentage_of_profit = 0
            },
            clickDelete(id) {

                this.$http.delete(`/${this.resource}/item-unit-type/${id}`)
                        .then(res => {
                            if(res.data.success) { 
                                this.loadRecord()
                                this.$message.success('Se eliminó correctamente el registro')                                 
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar eliminar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                
            },
            changeHasPerception(){
                if(!this.form.has_perception){
                    this.form.percentage_perception = null 
                } 

            },
            clickAddRow() {
                this.form.item_unit_types.push({
                    id: null,
                    description: null,
                    unit_type_id: 'NIU',
                    quantity_unit: 0,
                    price1: 0,
                    price2: 0,
                    price3: 0,
                    price_default: 2
                })
            },
            clickCancel(index) {
                this.form.item_unit_types.splice(index, 1)
                // this.initDocumentTypes()
                // this.showAddButton = true
            },
            calculatePercentageOfProfitBySale() {
                let difference = parseFloat(this.form.sale_unit_price) - parseFloat(this.form.purchase_unit_price);

                if(parseFloat(this.form.purchase_unit_price) === 0) {
                    this.form.percentage_of_profit = 0;
                } else {
                    if(this.enabled_percentage_of_profit) this.form.percentage_of_profit = difference / parseFloat(this.form.purchase_unit_price) * 100;
                }
            },
            calculatePercentageOfProfitByPurchase() {
                if(this.form.percentage_of_profit === '') {
                    this.form.percentage_of_profit = 0;
                }

                if(this.enabled_percentage_of_profit) this.form.sale_unit_price = (this.form.purchase_unit_price * (100 + parseFloat(this.form.percentage_of_profit))) / 100
            },
            calculatePercentageOfProfitByPercentage() {
                if(this.form.percentage_of_profit === '') {
                    this.form.percentage_of_profit = 0;
                }

                if(this.enabled_percentage_of_profit) this.form.sale_unit_price = (this.form.purchase_unit_price * (100 + parseFloat(this.form.percentage_of_profit))) / 100
            },
            submit() {
                
                if(this.form.individual_items.length < 2)
                    return this.$message.error('Al menos debe elegir 2 productos')

                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
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
            changeSystemIscType() {
                if (this.form.system_isc_type_id !== '03') {
                    this.form.suggested_price = 0
                }
            }
        }
    }
</script>