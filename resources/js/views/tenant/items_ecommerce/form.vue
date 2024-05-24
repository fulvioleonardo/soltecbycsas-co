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
                            <label class="control-label">Nombre  <span class="text-danger">*</span></label>
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
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_type_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unit_type_id" dusk="unit_type_id">
                                <el-option v-for="option in unit_types" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unit_type_id" v-text="errors.unit_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id" dusk="currency_type_id" filterable>
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

                    <div class="col-md-6">

                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Impuesto (Venta)</label>
                            <el-select v-model="form.tax_id" filterable>
                                <el-option v-for="option in taxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                        </div>
                    </div>

                    <div v-show="form.unit_type_id !=1" class="col-md-3 center-el-checkbox">
                        <div class="form-group" :class="{'has-danger': errors.calculate_quantity}">
                            <el-checkbox v-model="form.calculate_quantity">Calcular cantidad por precio</el-checkbox><br>
                            <small class="form-control-feedback" v-if="errors.calculate_quantity" v-text="errors.calculate_quantity[0]"></small>
                        </div>
                    </div>
                    <!-- <div class="col-md-3 center-el-checkbox" v-show="show_has_igv">
                        <div class="form-group" :class="{'has-danger': errors.has_igv}">
                            <el-checkbox v-model="form.has_igv">Incluye Igv</el-checkbox><br>
                            <small class="form-control-feedback" v-if="errors.has_igv" v-text="errors.has_igv[0]"></small>
                        </div>
                    </div> -->
                    <div class="col-md-3">
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

                    <div class="col-md-3" v-show="recordId==null">
                        <div class="form-group" :class="{'has-danger': errors.stock}">
                            <label class="control-label">Stock Inicial</label>
                            <el-input v-model="form.stock" ></el-input>
                            <small class="form-control-feedback" v-if="errors.stock" v-text="errors.stock[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.stock_min}">
                            <label class="control-label">Stock Mínimo</label>
                            <el-input v-model="form.stock_min"></el-input>
                            <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small>
                        </div>
                    </div>
                    <div v-show="form.unit_type_id != 1" class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.warehouse_id}">
                            <label class="control-label">Almacen</label>
                            <el-select v-model="form.warehouse_id" filterable>
                                <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.warehouse_id" v-text="errors.warehouse_id[0]"></small>
                        </div>
                    </div>

                    <!-- <div class="col-md-3 center-el-checkbox" >
                        <div class="form-group"  >
                            <el-checkbox v-model="has_percentage_perception" @change="changePercentagePerception">Incluye percepción</el-checkbox><br>
                        </div>
                    </div> -->

                    <!-- <div class="col-md-3 center-el-checkbox" v-show="has_percentage_perception">
                        <div class="form-group"  >
                            <label class="control-label">Porcentaje de percepción</label>

                            <el-input v-model="form.percentage_perception"></el-input>
                        </div>
                    </div> -->


                    <div v-if="attribute_types.length > 0" class="col-md-12">
                        <h5 class="separator-title ">
                           Atributos
                            <el-tooltip class="item" effect="dark" content="Diferentes presentaciones para la venta del producto" placement="top">
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                            <a href="#" class="control-label" @click.prevent="clickAddAttribute">[+ Agregar]</a>
                        </h5>
                    </div>
                    <div v-if="form.attributes.length > 0" class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.attributes" :key="index">
                                    <td>
                                        <el-select v-model="row.attribute_type_id" filterable @change="changeAttributeType(index)">
                                            <el-option v-for="option in attribute_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                        </el-select>
                                    </td>
                                    <td>
                                        <el-input v-model="row.value"></el-input>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click.prevent="clickRemoveAttribute(index)">x</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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
                                <div class="sub-title text-danger"><small>Se recomienda resoluciones Full Hd 1024x720</small></div>
                                <el-button type="primary" @click="openImages" >Agregar más fotos</el-button>

                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="row">

                                <div class="short-div col-md-8">

                                    <div class="form-group" :class="{'has-danger': errors.purchase_tax_id}">
                                        <label class="control-label">Impuesto (Compra)</label>
                                        <el-select v-model="form.purchase_tax_id" filterable>
                                            <el-option v-for="option in taxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.purchase_tax_id" v-text="errors.purchase_tax_id[0]"></small>
                                    </div>
                                </div>

                                <div class="short-div col-md-4">
                                    <div class="form-group" :class="{'has-danger': errors.purchase_unit_price}">
                                        <label class="control-label">Precio Unitario (Compra)</label>
                                        <el-input v-model="form.purchase_unit_price" dusk="purchase_unit_price" @input="calculatePercentageOfProfitByPurchase"></el-input>
                                        <small class="form-control-feedback" v-if="errors.purchase_unit_price" v-text="errors.purchase_unit_price[0]"></small>
                                    </div>
                                </div>
                                <div class="short-div col-md-4">
                                    <div class="form-group" :class="{'has-danger': errors.percentage_of_profit}">
                                        <label class="control-label">Porcentaje de ganancia (%)</label>
                                        <el-input v-model="form.percentage_of_profit" @input="calculatePercentageOfProfitByPercentage"></el-input>
                                        <small class="form-control-feedback" v-if="errors.percentage_of_profit" v-text="errors.percentage_of_profit[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-3 center-el-checkbox" >
                                    <div class="form-group"  >
                                        <el-checkbox v-model="form.apply_store">Aplica en Tienda</el-checkbox><br>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group" :class="{'has-danger': errors.warehouse_id}">
                                        <label class="control-label">Tags</label>
                                        <el-select multiple v-model="form.tags_id" filterable>
                                            <el-option v-for="option in tags" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                        <small class="form-control-feedback" v-if="errors.warehouse_id" v-text="errors.warehouse_id[0]"></small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    <!--<div class="col-md-12" v-if="form.warehouses">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Ubicación</th>
                                <th class="text-right">Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in form.warehouses">
                                <th>{{ row.warehouse_description }}</th>
                                <th class="text-right">{{ row.stock }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>-->
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

        <form-images ref="form_images"  @saveImages="saveImages" :showDialog.sync="showDialogImages" :recordId="recordId" ></form-images>

    </el-dialog>
</template>

<script>
    // import PercentagePerception from './partials/percentage_perception.vue'
    import FormImages from "./partials/form_images.vue";

    export default {
        props: ['showDialog', 'recordId', 'external'],
        components: {FormImages},

        data() {
            return {
                tags:[],
                warehouses: [],
                loading_submit: false,
                showPercentagePerception: false,
                has_percentage_perception: false,
                percentage_perception:null,
                titleDialog: null,
                resource: 'items',
                errors: {},
                headers: headers_token,
                form: {},
                unit_types: [],
                currency_types: [],
                system_isc_types: [],
                affectation_igv_types: [],
                taxes: [],
                accounts: [],
                show_has_igv:true,
                have_account:false,
                item_unit_type:{
                        id:null,
                        unit_type_id:null,
                        quantity_unit:0,
                        price1:0,
                        price2:0,
                        price3:0,
                        price_default:2,

                },
                showDialogImages: false,
                attribute_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.taxes = response.data.taxes
                    this.unit_types = response.data.unit_types
                    this.currency_types = response.data.currency_types
                    this.warehouses = response.data.warehouses
                    this.tags = response.data.tags
                    this.attribute_types = response.data.attribute_types

                    // this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                    // this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                })

            this.$eventHub.$on('submitPercentagePerception', (data)=>{
                this.form.percentage_perception = data
                if(!this.form.percentage_perception) this.has_percentage_perception = false
            })

        },
        methods: {
             clickAddAttribute() {
                this.form.attributes.push({
                    attribute_type_id: null,
                    description: null,
                    value: null,
                    start_date: null,
                    end_date: null,
                    duration: null,
                })
            },
            changeHaveAccount(){
                if(!this.have_account) this.form.account_id = null
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
            changePercentagePerception(){
                // if(this.has_percentage_perception){
                //     // this.percentage_perception = (this.recordId) ? this.form.percentage_perception:null
                // } else{
                //     this.form.percentage_perception = null
                // }

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
                    sale_unit_price: 0,
                    purchase_unit_price: 0,
                    calculate_quantity: false,
                    stock: 0,
                    is_set: false,
                    stock_min: 1,
                    item_unit_types:[],
                    percentage_of_profit: 0,
                    percentage_perception: 0,
                    image: null,
                    image_url: null,
                    temp_path: null,
                    account_id: null,
                    apply_store: false,
                    tags_id: [],
                    multi_images: [],
                    attributes: [],
                    tax_id: 1,
                    purchase_tax_id: 1,
                    currency_type_id: 170,
                }
                this.show_has_igv = true
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
                this.titleDialog = (this.recordId)? 'Editar Producto':'Nuevo Producto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
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
                            // this.changeAffectationIgvType()
                        })
                }
            },
            calculatePercentageOfProfitBySale() {
                let difference = parseFloat(this.form.sale_unit_price) - parseFloat(this.form.purchase_unit_price);

                if(parseFloat(this.form.purchase_unit_price) === 0) {
                    this.form.percentage_of_profit = 0;
                } else {
                    this.form.percentage_of_profit = difference / parseFloat(this.form.purchase_unit_price) * 100;
                }
            },
            calculatePercentageOfProfitByPurchase() {
                if(this.form.percentage_of_profit === '') {
                    this.form.percentage_of_profit = 0;
                }
                this.form.sale_unit_price = (this.form.purchase_unit_price * (100 + parseFloat(this.form.percentage_of_profit))) / 100
            },
            calculatePercentageOfProfitByPercentage() {
                if(this.form.percentage_of_profit === '') {
                    this.form.percentage_of_profit = 0;
                }
                this.form.sale_unit_price = (this.form.purchase_unit_price * (100 + parseFloat(this.form.percentage_of_profit))) / 100
            },
            submit() {
                // if(this.has_percentage_perception && !this.form.percentage_perception) return this.$message.error('Ingrese un porcentaje');
                // if(!this.has_percentage_perception) this.form.percentage_perception = null

                this.$refs.form_images.clear()

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
                this.$refs.form_images.clear()
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
            },
            openImages()
            {
                this.showDialogImages = true
            },
            saveImages(source)
            {

                this.form.multi_images = source
            },
            changeAttributeType(index) {
                let attribute_type_id = this.form.attributes[index].attribute_type_id
                let attribute_type = _.find(this.attribute_types, {id: attribute_type_id})
                this.form.attributes[index].description = attribute_type.description
            },
             clickRemoveAttribute(index) {
                this.form.attributes.splice(index, 1)
            },
        }
    }
</script>
