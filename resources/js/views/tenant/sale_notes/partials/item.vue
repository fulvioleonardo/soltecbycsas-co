<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto/Servicio
                                <a href="#" v-if="typeUser != 'seller'" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>
                            <el-select v-model="form.item_id" @change="changeItem" filterable>
                                <el-tooltip v-for="option in items"  :key="option.id" placement="top">
                                    <div slot="content">
                                        Marca: {{option.brand}} <br>
                                        Categoria: {{option.category}} <br>
                                        Stock: {{option.stock}} <br>
                                        Precio: {{option.currency_type_symbol}} {{option.sale_unit_price}} <br>
                                        Cod. Lote: {{option.lot_code}} <br>
                                        Fec. Venc: {{option.date_of_due}} <br>
                                    </div>
                                    <el-option :value="option.id" :label="option.full_description"></el-option>
                                </el-tooltip>

                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Impuesto</label>
                            <el-select v-model="form.tax_id"  filterable>
                                <el-option v-for="option in itemTaxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <!-- <el-checkbox :disabled="recordItem != null" v-model="change_tax_id">Editar</el-checkbox> -->
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="0.01"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price}">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div style="padding-top: 1%;" class="col-md-2 col-sm-2" v-if="form.item_id && form.item.lots_enabled && form.lots_group.length > 0">
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickLotGroup">[&#10004; Seleccionar lote]</a>
                    </div>

                    <div style="padding-top: 1%;" class="col-md-3 col-sm-3" v-if="form.item_id && form.item.series_enabled">
                        <!-- <el-button type="primary" native-type="submit" icon="el-icon-check">Elegir serie</el-button> -->
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickSelectLots">[&#10004; Seleccionar series]</a>
                    </div>

                    <!--<div class="col-md-6 mt-4" v-if="form.item_id && form.item.series_enabled">
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickSelectLots">[&#10004; Seleccionar series]</a>
                    </div> -->
                    
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group"  :class="{'has-danger': errors.discount}">
                            <label class="control-label">Descuento</label>
                            <el-input v-model="form.discount" :min="0" >
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.discount" v-text="errors.discount[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12"  v-if="form.item_unit_types.length > 0">
                        <div style="margin:3px" class="table-responsive">
                            <h3>Lista de Precios</h3>
                            <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center">Unidad</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Factor</th>
                                <th class="text-center">Precio 1</th>
                                <th class="text-center">Precio 2</th>
                                <th class="text-center">Precio 3</th>
                                <th class="text-center">Precio Default</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in form.item_unit_types" :key="index">

                                    <td class="text-center">{{row.unit_type.name}}</td>
                                    <td class="text-center">{{row.description}}</td>
                                    <td class="text-center">{{row.quantity_unit}}</td>
                                    <td class="text-center">{{row.price1}}</td>
                                    <td class="text-center">{{row.price2}}</td>
                                    <td class="text-center">{{row.price3}}</td>
                                    <td class="text-center">Precio {{row.price_default}}</td>
                                    <td class="series-table-actions text-right">
                                       <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="selectedPrice(row)">
                                            <i class="el-icon-check"></i>
                                        </button>
                                    </td>


                            </tr>
                            </tbody>
                        </table>

                        </div>

                    </div> 
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit" v-if="form.item_id">Agregar</el-button>
            </div>
        </form>
        <item-form  :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>


        <select-lots-form
            :showDialog.sync="showDialogSelectLots"
            :lots="lots"
            @addRowSelectLot="addRowSelectLot">
        </select-lots-form>

         <lots-group
            :showDialog.sync="showDialogLots"
            :lots_group="form.lots_group"
            @addRowLotGroup="addRowLotGroup">
        </lots-group>

    </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>
<script>

    import itemForm from '../../items/form.vue'
    import SelectLotsForm from './lots.vue'
    import LotsGroup from './lots_group.vue'


    export default {
        props: ['showDialog', 'currencyTypeIdActive', 'exchangeRateSale', 'typeUser'],
        components: {itemForm, SelectLotsForm, LotsGroup},
        data() {
            return {
                titleDialog: 'Agregar Producto o Servicio',
                resource: 'sale-notes',
                showDialogNewItem: false,
                showDialogSelectLots: false,
                errors: {},
                form: {},
                items: [],
                affectation_igv_types: [],
                system_isc_types: [],
                discount_types: [],
                charge_types: [],
                attribute_types: [],
                use_price: 1,
                change_affectation_igv_type_id: false,
                lots:[],
                taxes:[],
                showDialogLots: false
            }
        },
        computed: {
            itemTaxes() {
                return this.taxes.filter(tax => !tax.is_retention);
            },
        },
        created() {
            this.initForm()
            this.getTables()
            this.$eventHub.$on('reloadDataItems', (item_id) => {
                this.reloadDataItems(item_id)
            })
        },
        methods: {
            addRowSelectLot(lots){
                this.lots = lots
            },
            async clickSelectLots(){
                this.showDialogSelectLots = true
            },
            getTables(){

                this.$http.get(`/${this.resource}/item/tables`).then(response => {
                    this.items = response.data.items
                    this.taxes = response.data.taxes;
                    // this.filterItems()

                })
            },
            selectedPrice(row)
            {
                // debugger
                let valor = 0
                switch(row.price_default)
                {
                    case 1:
                        valor = row.price1
                        break
                    case 2:
                         valor = row.price2
                        break
                    case 3:
                         valor = row.price3
                        break

                }

                this.item_unit_type = row
                this.form.unit_price = valor
                this.form.item.unit_type_id = row.unit_type_id
                this.form.item_unit_type_id = row.id
                this.getTables()

            },
            filterItems(){
                this.items = this.items.filter(item => item.warehouses.length >0)
            },
            initForm() {
                this.errors = {}
                this.form = {
                    item_id: null,
                    item: {},
                    quantity: 1,
                    unit_price: 0,
                    is_set: false,
                    item_unit_types: [],
                    series_enabled: false,
                    IdLoteSelected: null,
                    lots_group: [],
                    
                    subtotal: null,
                    tax: {},
                    tax_id: null,
                    total: 0,
                    total_tax: 0,
                    unit_type: {},
                    discount: 0,
                    unit_type_id: null,

                }
                this.item_unit_type = {};
                this.lots = []
            },
            // initializeFields() {
            //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
            // },
            create() {
            //     this.initializeFields()
            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            async changeItem() {

                this.form.item = await _.find(this.items, {'id': this.form.item_id})
                this.lots = this.form.item.lots
                // console.log(this.form.item.lots)

                this.form.item_unit_types = _.find(this.items, {'id': this.form.item_id}).item_unit_types
                this.form.unit_price = this.form.item.sale_unit_price

                this.form.unit_type_id = this.form.item.unit_type_id
                this.form.tax_id = (this.taxes.length > 0) ? this.form.item.tax_id: null

                this.form.quantity = 1;
                this.form.lots_group = this.form.item.lots_group

            },
            async clickAddItem() {


                if(this.form.item.lots_enabled){
                    if(!this.form.IdLoteSelected)
                        return this.$message.error('Debe seleccionar un lote.');
                }

                let unit_price = this.form.unit_price;

                this.form.unit_price = unit_price;
                this.form.item.unit_price = unit_price;

                this.form.item.presentation = this.item_unit_type;

                let IdLoteSelected = this.form.IdLoteSelected

                this.form.tax = _.find(this.taxes, {'id': this.form.tax_id})
                this.form.unit_type = this.form.item.unit_type


                let select_lots = await _.filter(this.form.item.lots, {'has_sale':true})
                let un_select_lots = await _.filter(this.form.item.lots, {'has_sale':false})

                if(this.form.item.series_enabled){
                    if(select_lots.length != this.form.quantity)
                        return this.$message.error('La cantidad de series seleccionadas son diferentes a la cantidad a vender');
                }

                this.form.item.lots = un_select_lots
                this.form.lots = select_lots

                this.form.IdLoteSelected = IdLoteSelected

                // console.log(un_select_lots)
                // console.log(this.row.lots)

                // this.initializeFields()
                this.$emit('add', this.form)

                this.initForm()

            },
            reloadDataItems(item_id) {
                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.items = response.data
                    this.form.item_id = item_id
                    if(item_id){
                        this.changeItem()
                    }
                    // this.filterItems()

                })
            },
            addRowLotGroup(id)
            {
                this.form.IdLoteSelected =  id
            },
             clickLotGroup()
            {
                this.showDialogLots = true
            },
        }
    }

</script>
