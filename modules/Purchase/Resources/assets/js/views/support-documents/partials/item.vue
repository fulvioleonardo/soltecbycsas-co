<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" top="7vh" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
                        <div class="form-group" id="custom-select" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto/Servicio
                                <a href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>

                            <el-input id="custom-input">
                                <el-select v-model="form.item_id"
                                        @change="changeItem"
                                        filterable
                                        remote
                                        placeholder="Buscar"
                                        popper-class="el-select-items"
                                        @visible-change="focusTotalItem"
                                        slot="prepend"
                                        id="select-width"
                                        :remote-method="searchRemoteItems"
                                        :loading="loading_search">

                                    <el-tooltip v-for="option in items"  :key="option.id" placement="top">

                                        <div slot="content">
                                            Marca: {{option.brand}} <br>
                                            Categoria: {{option.category}} <br>
                                            Stock: {{option.stock}} <br>
                                            Precio: {{option.currency_type_symbol}} {{option.sale_unit_price}} <br>
                                        </div>

                                        <el-option  :value="option.id" :label="option.full_description"></el-option>

                                    </el-tooltip>

                                </el-select>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Impuesto</label>
                            <el-select v-model="form.tax_id"  :disabled="false">
                                <el-option v-for="option in itemTaxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="0.01" :disabled="form.item.calculate_quantity"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.price}">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.price" @input="calculateQuantity" >
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>

                    <!-- <div style="padding-top: 1%;" class="col-md-2 col-sm-2" v-if="form.item_id && form.item.lots_enabled && form.lots_group.length > 0">
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickLotGroup">[&#10004; Seleccionar lote]</a>
                    </div>

                    <div style="padding-top: 1%;" class="col-md-3 col-sm-3" v-if="form.item_id && form.item.series_enabled">
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickSelectLots">[&#10004; Seleccionar series]</a>
                    </div> -->


                    <div class="col-md-3 col-sm-6" v-show="form.item.calculate_quantity">
                        <div class="form-group"  :class="{'has-danger': errors.total_item}">
                            <label class="control-label">Total venta producto</label>
                            <el-input v-model="total_item" @input="calculateQuantity" :min="0.01" ref="total_item">
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.total_item" v-text="errors.total_item[0]"></small>
                        </div>
                    </div>


                    <div class="col-md-3 col-sm-6">
                        <div class="form-group"  :class="{'has-danger': errors.discount}">
                            <label class="control-label">Descuento</label>
                            <el-input v-model="form.discount" :min="0" >
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.discount" v-text="errors.discount[0]"></small>
                        </div>
                    </div>

                    <template v-if="!isFromAdjustNote">
                        <div class="col-md-3">
                            <div class="form-group" :class="{'has-danger': errors.type_generation_transmition_id}">
                                <label class="control-label">Tipo de envío</label>
                                <el-select v-model="form.type_generation_transmition_id">
                                    <el-option v-for="option in type_generation_transmitions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.type_generation_transmition_id" v-text="errors.type_generation_transmition_id[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group" :class="{'has-danger': errors.start_date}">
                                <label class="control-label">Fecha de inicio</label>
                                <el-date-picker :disabled="form.type_generation_transmition_id === 1" v-model="form.start_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeStartDate"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.start_date" v-text="errors.start_date[0]"></small>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
            <div class="form-actions text-right mt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button class="add" type="primary" native-type="submit" v-if="form.item_id">{{titleAction}}</el-button>
            </div>
        </form>

        <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>

    </el-dialog>
</template>

<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>

<script>

    import ItemForm from '@views/items/form.vue'

    export default {
        // props: ['showDialog', 'currencyTypeSymbolActive', 'dateOfIssue'],
        props: {
            showDialog: Boolean,
            currencyTypeSymbolActive: String,
            dateOfIssue: String,
            isFromAdjustNote: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        components: {ItemForm},
        data() {
            return {
                loading_search:false,
                titleAction: '',
                is_client:false,
                titleDialog: '',
                resource: 'support-documents',
                showDialogNewItem: false,
                errors: {},
                form: {},
                all_items: [],
                items: [],
                total_item: 0,
                warehousesDetail:[],
                showListStock:false,
                taxes:[],
                type_generation_transmitions:[],
            }
        },
        computed: {
            itemTaxes() {
                return this.taxes.filter(tax => !tax.is_retention);
            },
        },
        created() {
            this.events()
            this.initForm()
            this.getTables()
        },
        methods: {
            changeStartDate()
            {
                if(this.form.type_generation_transmition_id == 1)
                {
                    this.setDefaultStartDate()
                }
                else
                {
                    if(moment(this.form.start_date).isAfter(this.dateOfIssue))
                    {
                        this.setDefaultStartDate()
                        this.$message.error('Fecha de inicio no puede ser mayor a la fecha del documento')
                    }

                    const min_date = moment(this.dateOfIssue).subtract(6, 'days')

                    if(moment(this.form.start_date) < min_date)
                    {
                        this.setDefaultStartDate()
                        this.$message.error('Fecha de inicio no puede ser menor a 6 días de la fecha del documento')
                    }
                }

            },
            setDefaultStartDate()
            {
                this.form.start_date = this.dateOfIssue
            },
            getTables()
            {
                this.$http.get(`/${this.resource}/item/tables`).then(response => {
                    this.taxes = response.data.taxes
                    this.type_generation_transmitions = response.data.type_generation_transmitions
                    this.all_items = response.data.items
                    this.filterItems()
                })
            },
            events()
            {
                this.$eventHub.$on('reloadDataItems', (item_id) => {
                    this.reloadDataItems(item_id)
                })
            },
            async searchRemoteItems(input) {

                if (input.length > 2) {

                    this.loading_search = true
                    let parameters = `input=${input}`


                    await this.$http.get(`/main-items/search/?${parameters}`)
                            .then(response => {
                                // console.log(response)
                                this.items = response.data.items
                                this.loading_search = false


                                if(this.items.length == 0){
                                    this.filterItems()
                                }
                            })
                } else {
                    await this.filterItems()
                }

            },
            filterItems() {
                this.items = this.all_items
            },
            clickWarehouseDetail(){

                if(!this.form.item_id){
                    return this.$message.error('Seleccione un item');
                }

                let item = _.find(this.items, {'id': this.form.item_id});

                this.warehousesDetail = item.warehouses
                this.showWarehousesDetail = true
            },
            initForm() {
                this.errors = {}

                this.form = {
                    id: null,
                    item_id: null,
                    item: {},
                    code: null,
                    discount: 0,
                    name: null,
                    price: null,
                    unit_price: 0,
                    quantity: null,
                    subtotal: null,
                    tax: {},
                    tax_id: null,
                    total: 0,
                    total_tax: 0,
                    type_unit: {},
                    unit_type_id: null,
                    type_generation_transmition_id: this.isFromAdjustNote ? null : 1,
                    start_date: null
                }

                this.total_item = 0
                this.item_unit_type = {}

            },
            async create() {

                this.titleDialog = 'Agregar Producto o Servicio'
                this.titleAction = 'Agregar'

            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            async changeItem()
            {
                this.form.item = _.find(this.items, {'id': this.form.item_id});
                this.form.unit_type_id = this.form.item.unit_type_id

                if(!this.isFromAdjustNote)
                {
                    if(this.form.type_generation_transmition_id == 1) this.setDefaultStartDate()
                }

                // this.lots = this.form.item.lots

                // this.form.tax_id = (this.taxes.length > 0) ? this.form.item.tax.id: null
                this.form.tax_id = 8 // se asigna excento por defecto

                this.form.price = this.form.item.sale_unit_price;

                this.form.quantity = 1;
                this.cleanTotalItem();
                this.showListStock = true
                this.form.lots_group = this.form.item.lots_group

            },
            focusTotalItem(change) {
                if(!change && this.form.item.calculate_quantity) {
                    this.$refs.total_item.$el.getElementsByTagName('input')[0].focus()
                    this.total_item = this.form.unit_price_value
                }
            },
            calculateQuantity() {
                // debugger
                if(this.form.item.calculate_quantity) {
                    //console.log('entro')
                    this.form.quantity = _.round((this.total_item / this.form.price), 4)
                }
            },
            cleanTotalItem(){
                this.total_item = null
            },
            async clickAddItem() {
                if(this.form.item.lots_enabled){
                    if(!this.form.IdLoteSelected)
                        return this.$message.error('Debe seleccionar un lote.');
                }

                if (this.validateTotalItem().total_item) return;

                this.form.tax = _.find(this.taxes, {'id': this.form.tax_id})
                this.form.type_unit = this.form.item.type_unit
                // console.log(this.form)
                this.form.item.presentation = this.item_unit_type;

                let IdLoteSelected = this.form.IdLoteSelected

                let select_lots = await _.filter(this.form.item.lots, {'has_sale':true})
                let un_select_lots = await _.filter(this.form.item.lots, {'has_sale':false})

                if(this.form.item.series_enabled){
                    if(select_lots.length != this.form.quantity)
                        return this.$message.error('La cantidad de series seleccionadas son diferentes a la cantidad a vender');
                }

                this.form.IdLoteSelected = IdLoteSelected
                this.form.unit_price = this.form.price

                this.$emit('add', this.form);

                this.initForm();

            },
            validateTotalItem(){

                this.errors = {}

                if(this.form.item.calculate_quantity){
                    if(this.total_item < 0.01)
                        this.$set(this.errors, 'total_item', ['total venta item debe ser mayor a 0.01']);
                }

                return this.errors
            },
            reloadDataItems(item_id) {

                this.$http.get(`/main-items/search-by-id/${item_id}`).then((response) => {
                    this.items = response.data.items
                    this.form.item_id = item_id
                    this.changeItem()
                })

            },
            selectedPrice(row)
            {

                // console.log(row)
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
                this.form.item_unit_type_id = row.id
                this.item_unit_type = row

                this.form.price = valor
                this.form.item.unit_type_id = row.unit_type_id
                // this.form.quantity = row.quantity_unit
                this.calculateQuantity()
                // console.log(this.form)
            },
            addRowLotGroup(id)
            {
                this.form.IdLoteSelected =  id
            },
            clickLotGroup()
            {
                this.showDialogLots = true
            },
            async clickSelectLots(){
                this.showDialogSelectLots = true
            },
            addRowSelectLot(lots){
                this.lots = lots
            },
        }
    }

</script>
