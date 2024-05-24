<template>
<div>
    <header class="page-header pr-0">
        <!-- <h2 class="text-sm">POS</h2>
      <div class="right-wrapper pull-right">
        <h2 class="text-sm pr-5">T/C 3.321</h2>
        <h2 class="text-sm">{{user.name}}</h2>
      </div> -->
        <div class="row">
            <div class="col-md-6">
                <!-- <h2 class="text-sm">POS</h2> -->
                <h2>
                    <el-switch v-model="search_item_by_barcode" active-text="Buscar por código de barras" @change="changeSearchItemBarcode"></el-switch>
                </h2>
                <template v-if="!electronic">
                    <h2>
                        <el-switch v-model="type_refund" active-text="Devolución"></el-switch>
                    </h2>
                </template>
            </div>
            <div class="col-md-4">
                <h2> <button type="button" @click="place = 'cat'" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-border-all"></i></button> </h2>
                <h2> <button type="button" :disabled="place == 'cat2'" @click="setView" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-bars"></i></button> </h2>
                <h2> <button type="button" :disabled="place== 'cat'" @click="back()" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-undo"></i></button> </h2>
            </div>
            <div class="col-md-2">
                <div class="right-wrapper">
                    <!-- <h2 class="text-sm pr-5">T/C  {{form.exchange_rate_sale}}</h2> -->
                    <h2 class="text-sm  pull-right">{{user.name}}</h2>
                </div>
            </div>
        </div>
    </header>

    <div v-if="plate_number_valid">
        <div v-if="!is_payment" class="row col-lg-12 m-0 p-0" v-loading="loading">
            <div class="col-lg-8 col-md-6 px-4 pt-3 hyo">

                <template v-if="!search_item_by_barcode">
                    <el-input v-show="place  == 'prod' || place == 'cat2'" placeholder="Buscar productos" size="medium" v-model="input_item" @input="searchItems" autofocus class="m-bottom">
                        <el-button slot="append" icon="el-icon-plus" @click.prevent="showDialogNewItem = true"></el-button>
                    </el-input>
                </template>
                <template v-else>
                    <el-input v-show="place  == 'prod' || place == 'cat2'" placeholder="Buscar productos" size="medium" v-model="input_item" @change="searchItemsBarcode" autofocus class="m-bottom">
                        <el-button slot="append" icon="el-icon-plus" @click.prevent="showDialogNewItem = true"></el-button>
                    </el-input>
                </template>

                <div v-if="place == 'cat2'" class="container testimonial-group">
                    <div class="row text-center flex-nowrap">
                        <div v-for="(item, index) in categories" @click="filterCategorie(item.id, true)" :style="{ backgroundColor: item.color}" :key="index" class="col-sm-3 pointer">{{item.name}}</div>
                    </div>
                </div> <br>

                <div v-if="place == 'cat'" class="row no-gutters">
                    <div v-for="(item, index) in categories" class="col" :key="index">
                        <div @click="filterCategorie(item.id)" class="card p-0 m-0 mb-1 mr-1 text-center">
                            <div :style="{ backgroundColor: item.color}" class="card-body pointer rounded-0" style="font-weight: bold;color: white;font-size: 18px;">
                                {{item.name}}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="place == 'prod' || place == 'cat2'" class="row pos-items">
                    <template v-for="(item,index) in items">
                        <div v-bind:class="classObjectCol" :key="index">
                            <section class="card ">
                                <div class="card-body pointer px-2 pt-2" @click="clickAddItem(item,index)">
                                    <p class="font-weight-semibold mb-0" v-if="item.name.length > 50" data-toggle="tooltip" data-placement="top" :title="item.name">
                                        {{item.name.substring(0,50)}}
                                    </p>
                                    <p class="font-weight-semibold mb-0" v-if="item.name.length < 50">{{item.name}}</p>
                                    <img :src="item.image_url" class="img-thumbail img-custom" />
                                    <p class="text-muted font-weight-lighter mb-0">
                                        <small>{{item.internal_id}}</small>
                                        <template v-if="item.sets.length  > 0">
                                            <br>
                                            <small> {{ item.sets.join('-') }} </small>
                                        </template>
                                    </p>
                                </div>
                                <div class="card-footer pointer text-center bg-primary">
                                    <template v-if="!item.edit_unit_price">
                                        <h5 class="font-weight-semibold text-right text-white">
                                            <button
                                                type="button"
                                                class="btn btn-xs btn-primary-pos"
                                                @click="clickOpenInputEditUP(index)">
                                                <span style="font-size:16px;">&#9998;</span>
                                            </button>
                                            {{currency.symbol}} {{ item.sale_unit_price_with_tax }}
                                        </h5>
                                    </template>
                                    <template v-else>
                                        <el-input min="0" v-model="item.edit_sale_unit_price" class="mt-3 mb-3" size="mini">
                                            <el-button slot="append" icon="el-icon-check" type="primary" @click="clickEditUnitPriceItem(index)"></el-button>
                                            <el-button slot="append" icon="el-icon-close" type="danger" @click="clickCancelUnitPriceItem(index)"></el-button>
                                        </el-input>
                                    </template>
                                </div>

                                <div v-if="configuration.options_pos" class=" card-footer  bg-primary btn-group flex-wrap" style="width:100% !important; padding:0 !important; ">
                                    <el-row style="width:100%">
                                        <el-col :span="6">
                                            <el-tooltip class="item" effect="dark" content="Visualizar stock" placement="bottom-end">
                                                <button type="button" style="width:100% !important;" class="btn btn-xs btn-primary-pos" @click="clickWarehouseDetail(item)">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </el-tooltip>
                                        </el-col>
                                        <el-col :span="6">
                                            <el-tooltip class="item" effect="dark" content="Visualizar historial de ventas del producto (precio venta) y cliente" placement="bottom-end">
                                                <button type="button" style="width:100% !important;" class="btn btn-xs btn-primary-pos" @click="clickHistorySales(item.item_id)"><i class="fa fa-list"></i></button>
                                            </el-tooltip>
                                        </el-col>
                                        <el-col :span="6">
                                            <el-tooltip class="item" effect="dark" content="Visualizar historial de compras del producto (precio compra)" placement="bottom-end">
                                                <button type="button" style="width:100% !important;" class="btn btn-xs btn-primary-pos" @click="clickHistoryPurchases(item.item_id)"><i class="fas fa-cart-plus"></i></button>
                                            </el-tooltip>
                                        </el-col>
                                        <el-col :span="6">
                                            <el-tooltip class="item" effect="dark" content="Visualizar lista de precios disponibles" placement="bottom-end">
                                                <el-popover placement="top" title="Precios" width="400" trigger="click">
                                                    <el-table v-if="item.item_unit_types" :data="item.item_unit_types">
                                                        <el-table-column width="140" label="Descripción" property="description"></el-table-column>
                                                        <el-table-column width="80" label="Unidad" property="unit_type_name"></el-table-column>
                                                        <el-table-column width="80" label="Precio">
                                                            <template slot-scope="{row}">
                                                                <span v-if="row.price_default == 1">{{ row.price1 }}</span>
                                                                <span v-else-if="row.price_default == 2">{{ row.price2 }}</span>
                                                                <span v-else-if="row.price_default == 3">{{ row.price3 }}</span>
                                                            </template>
                                                        </el-table-column>
                                                        <el-table-column width="70" label="">
                                                            <template slot-scope="{row}">
                                                                <button @click="setListPriceItem(row,index)" type="button" class="btn btn-custom btn-xs">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </template>
                                                        </el-table-column>
                                                    </el-table>
                                                    <button type="button" slot="reference" style="width:100% !important;" class="btn btn-xs btn-primary-pos"><i class="fas fa-money-bill-alt"></i></button>
                                                </el-popover>
                                            </el-tooltip>
                                        </el-col>
                                    </el-row>
                                </div>

                                <!-- <div v-if="configuration.options_pos" class=" card-footer  bg-primary btn-group flex-wrap" style="width:100% !important; padding:0 !important; ">

                                    <el-tooltip class="item" effect="dark" content="Visualizar stock" placement="bottom-end">
                                        <button type="button" style="width:25% !important;" class="btn btn-xs btn-primary-pos" @click="clickWarehouseDetail(item)">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </el-tooltip>

                                    <el-tooltip class="item" effect="dark" content="Visualizar historial de ventas del producto (precio venta) y cliente" placement="bottom-end">
                                        <button type="button" style="width:25% !important;" class="btn btn-xs btn-primary-pos" @click="clickHistorySales(item.item_id)"><i class="fa fa-list"></i></button>
                                    </el-tooltip>

                                    <el-tooltip class="item" effect="dark" content="Visualizar historial de compras del producto (precio compra)" placement="bottom-end">
                                        <button type="button" style="width:25% !important;" class="btn btn-xs btn-primary-pos" @click="clickHistoryPurchases(item.item_id)"><i class="fas fa-cart-plus"></i></button>
                                    </el-tooltip>

                                    <el-tooltip class="item" effect="dark" content="Visualizar lista de precios disponibles" placement="bottom-end">
                                        <el-popover placement="top" title="Precios" width="370" trigger="click">
                                            <el-table v-if="item.item_unit_types" :data="item.item_unit_types">
                                                <el-table-column width="90" label="Precio">
                                                    <template slot-scope="{row}">
                                                        <span v-if="row.price_default == 1">{{row.price1}}</span>
                                                        <span v-else-if="row.price_default == 2">{{row.price2}}</span>
                                                        <span v-else-if="row.price_default == 3">{{row.price3}}</span>
                                                    </template>
                                                </el-table-column>
                                                <el-table-column width="80" label="Unidad" property="unit_type_id"></el-table-column>
                                                <el-table-column width="120" label="Descripción" property="description"></el-table-column>

                                                <el-table-column width="80" label="">
                                                    <template slot-scope="{row}">
                                                        <button @click="setListPriceItem(row,index)" type="button" class="btn btn-custom btn-xs">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </template>
                                                </el-table-column>
                                            </el-table>
                                            <button type="button" slot="reference" style="width:100% !important;" class="btn btn-xs btn-primary-pos"><i class="fas fa-money-bill-alt"></i></button>
                                        </el-popover>
                                    </el-tooltip>
                                </div> -->
                            </section>
                        </div>
                    </template>
                </div>
                <div v-if="place == 'prod' || place == 'cat2'" class="row">
                    <div class="col-md-12 text-center">
                        <el-pagination
                            @current-change="getRecords"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 bg-white m-0 p-0" style="height: calc(100vh - 110px)">
                <div class="h-75 bg-light" style="overflow-y: auto">
                    <div class="row py-3 border-bottom m-0 p-0">
                        <div class="col-8">
                            <el-select ref="select_person" v-model="form.customer_id" filterable placeholder="Cliente" @change="changeCustomer" @keyup.native="keyupCustomer" @keyup.enter.native="keyupEnterCustomer">
                                <el-option v-for="option in all_customers" :key="option.id" :label="option.description" :value="option.id"></el-option>
                            </el-select>
                        </div>
                        <div class="col-4">
                            <div class="btn-group d-flex" role="group">
                                <a class="btn btn-sm btn-default w-100" @click.prevent="showDialogNewPerson = true">
                                    <i class="fas fa-plus fa-wf"></i>
                                </a>
                                <a class="btn btn-sm btn-default w-100" @click="clickDeleteCustomer">
                                    <i class="fas fa-trash fa-wf"></i>
                                </a>
                                <!-- <a class="btn btn-sm btn-default w-100" @click="selectCurrencyType"> -->
                                <!-- <template v-if="form.currency_id == 'PEN'">
                        <strong>S/</strong>
                      </template>
                      <template v-else>
                        <strong>$</strong>
                      </template> -->
                                <!-- <i class="fa fa-usd" aria-hidden="true"></i> -->
                                <!-- </a> -->
                            </div>
                        </div>
                    </div>
                    <div class="row py-1 border-bottom m-0 p-0">
                        <div class="col-12">
                            <table class="table table-sm table-borderless mb-0">
                                <template v-for="(item,index) in form.items">
                                    <tr :key="index">
                                        <td width="5%" style="text-align: center;" class="pos-list-label" v-if="item.unit_type">
                                            {{ item.unit_type.name }}
                                        </td>
                                        <td width="20%">
                                            <el-input v-model="item.item.aux_quantity" :readonly="item.item.calculate_quantity" class @input="clickAddItem(item,index,true)"></el-input>
                                        </td>
                                        <td width="20%">
                                            <p class="m-0">{{item.item.name}}</p>
                                            <small> {{nameSets(item.item_id)}} </small>
                                        </td>
                                        <td>
                                            <p class="font-weight-semibold m-0 text-center">{{currency.symbol}}</p>
                                        </td>
                                        <td width="20%">
                                            <p class="font-weight-semibold m-0 text-center">
                                                <el-input v-model="item.sale_unit_price_with_tax" class @input="clickAddItem(item,index,true)" :readonly="item.item.calculate_quantity">
                                                </el-input>
                                            </p>
                                        </td>
                                        <td width="30%">
                                            <p class="font-weight-semibold m-0 text-center">
                                                <el-input v-model="item.total" @input="calculateQuantity(index)" :readonly="!item.item.calculate_quantity">
                                                </el-input>
                                            </p>
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-sm btn-default" @click="clickDeleteItem(index)">
                                                <i class="fas fa-trash fa-wf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                                <template v-for="(item,index) in items_refund">
                                    <tr :key="index + 'R'">
                                        <td width="5%" style="text-align: center;" class="pos-list-label" v-if="item.unit_type">
                                            {{ item.unit_type.name }}
                                        </td>
                                        <td width="20%">
                                            <el-input :value=" '-' +item.quantity" :readonly="true" class></el-input>
                                        </td>
                                        <td width="20%">
                                            <p class="m-0">{{item.item.name}}</p>
                                            <small> {{nameSets(item.item_id)}} </small>
                                        </td>
                                        <td>
                                            <p class="font-weight-semibold m-0 text-center">{{currency.symbol}}</p>
                                        </td>
                                        <td width="20%">
                                            <p class="font-weight-semibold m-0 text-center">
                                                <el-input v-model="item.sale_unit_price_with_tax" class @input="clickAddItem(item,index,true)" :readonly="item.item.calculate_quantity">
                                                </el-input>
                                            </p>
                                        </td>
                                        <td width="30%">
                                            <p class="font-weight-semibold m-0 text-center">
                                                <el-input :value="'-' + item.total" :readonly="true">
                                                </el-input>
                                            </p>
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-sm btn-default" @click="clickDeleteItemRefund(index)">
                                                <i class="fas fa-trash fa-wf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="h-25 bg-light" style="overflow-y: auto">
                    <div class="row border-top bg-light m-0 p-0 h-50 d-flex align-items-right pr-5 pt-2">

                        <div class="col-md-12" style="display: flex; flex-direction: column; align-items: flex-end;">
                            <table>
                                <tr class="font-weight-semibold  m-0" v-if="form.sale > 0">
                                    <td class="font-weight-semibold">SUBTOTAL</td>
                                    <td class="font-weight-semibold">:</td>
                                    <td class="text-right text-blue">{{currency.symbol}} {{ form.sale }}</td>
                                </tr>
                                <tr class="font-weight-semibold  m-0" v-if="form.total_discount > 0">
                                    <td class="font-weight-semibold">TOTAL DESCUENTO (-)</td>
                                    <td class="font-weight-semibold">:</td>
                                    <td class="text-right text-blue">{{currency.symbol}} {{ form.total_discount }}</td>
                                </tr>
                                <template v-for="(tax, index) in form.taxes">
                                    <tr v-if="((tax.total > 0) && (!tax.is_retention))" :key="index" class="font-weight-semibold  m-0">
                                        <td class="font-weight-semibold">
                                            {{tax.name}}[+]
                                        </td>
                                        <td class="font-weight-semibold">:</td>
                                        <td class="text-right text-blue">{{currency.symbol}} {{Number(tax.total).toFixed(2)}}</td>
                                    </tr>
                                </template>
                                <tr class="font-weight-semibold  m-0" v-if="form.subtotal > 0">
                                    <td class="font-weight-semibold">TOTAL VENTA</td>
                                    <td class="font-weight-semibold">:</td>
                                    <td class="text-right text-blue">{{currency.symbol}} {{ form.subtotal }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row text-white m-0 p-0 h-50 d-flex align-items-center" @click="clickPayment" v-bind:class="[form.total > 0 ? 'bg-info pointer' : 'bg-dark']">
                        <div class="col-6 text-center h5">
                            <i class="fa fa-chevron-circle-right"></i>
                            <span class="font-weight-semibold">PAGO</span>
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="font-weight-semibold h5">{{currency.symbol}} {{ form.total }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <person-form :showDialog.sync="showDialogNewPerson" type="customers" :input_person="input_person" :external="true" :document_type_id="form.document_type_id"></person-form>

            <item-form :showDialog.sync="showDialogNewItem" :external="true"></item-form>
        </div>
        <template v-else>
            <payment-form :is_payment.sync="is_payment" :form="form" :items_refund="items_refund" :currency-type-id-active="form.currency_id" :currency-type-active="currency" :exchange-rate-sale="form.exchange_rate_sale" :customer="customer" :soapCompany="soapCompany"></payment-form>
        </template>

        <history-sales-form :showDialog.sync="showDialogHistorySales" :item_id="history_item_id" :customer_id="form.customer_id"></history-sales-form>

        <history-purchases-form :showDialog.sync="showDialogHistoryPurchases" :item_id="history_item_id"></history-purchases-form>

        <warehouses-detail :showDialog.sync="showWarehousesDetail" :warehouses="warehousesDetail" :unit_type="unittypeDetail"></warehouses-detail>
    </div>
    <div v-else>
        <div class="text-center">
            <br>
            <br>
            <br>
            <br>
            <br>
            <i class="fas fa-chevron-circle-right fa fw h5"></i>
            <span class="font-weight-semibold h5">USUARIO NO VALIDO PARA ESTA CAJA</span>
            <i class="fas fa-chevron-circle-left fa fw h5"></i>
        </div>
    </div>
</div>
</template>

<style>
/* The heart of the matter */
.testimonial-group>.row {
    overflow-x: auto;
    white-space: nowrap;
    overflow-y: hidden;
}

.testimonial-group>.row>.col-sm-3 {
    display: inline-block;
    float: none;
}

/* Decorations */
.col-sm-3 {
    height: 70px;
    margin-right: 0.5%;
    color: white;
    font-size: 18px;
    padding-bottom: 20px;
    padding-top: 18px;
    font-weight: bold
}

.card-block {
    min-height: 220px;
}

.ex1 {
    overflow-x: scroll;
}

.cat_c {
    width: 100px;
    margin: 1%;
    padding: 3px;
    font-weight: bold;
    color: white;
    min-height: 90px;
}

.cat_c p {
    color: white;
}

.c-width {
    width: 80px !important;
    padding: 0 !important;
    margin-right: 0 !important;
}

.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 1% !important;
}

.el-input-group__append {
    padding: 0 10px !important;
}
</style>

<script>
// import { calculateRowItem } from "../../../helpers/functions";
import PaymentForm from "./partials/payment.vue";
import ItemForm from "./partials/form.vue";
// import { functions, exchangeRate } from "../../../mixins/functions";
import HistorySalesForm from "../../../../../modules/Pos/Resources/assets/js/views/history/sales.vue";
import HistoryPurchasesForm from "../../../../../modules/Pos/Resources/assets/js/views/history/purchases.vue";
import PersonForm from "../persons/form.vue";
import WarehousesDetail from '../items/partials/warehouses.vue'
import queryString from "query-string";

export default {
    props: ['configuration', 'soapCompany'],
    components: {
        PaymentForm,
        ItemForm,
        HistorySalesForm,
        HistoryPurchasesForm,
        PersonForm,
        WarehousesDetail
    },
    // mixins: [functions, exchangeRate],

    data() {
        return {
            place: 'cat',
            history_item_id: null,
            search_item_by_barcode: false,
            warehousesDetail: [],
            unittypeDetail: [],
            input_person: {},
            showDialogHistoryPurchases: false,
            showDialogHistorySales: false,
            showDialogNewPerson: false,
            showDialogNewItem: false,
            loading: false,
            is_payment: false, //aq
            // is_payment: true,//aq
            showWarehousesDetail: false,
            resource: "pos",
            recordId: null,
            input_item: "",
            items: [],
            all_items: [],
            customers: [],
            currencies: [],
            taxes: [],
            all_customers: [],
            establishment: null,
            currency: {},
            form_item: {},
            customer: {},
            row: {},
            user: {},
            form: {},
            categories: [],
            colors: ['#1cb973', '#bf7ae6', '#fc6304', '#9b4db4', '#77c1f3'],
            type_refund: false,
            items_refund: [],
            pagination: {},
            category_selected: "",
            plate_number_valid: true,
            electronic: false,
        };
    },

    mounted(){
    },

    async created() {
        this.electronic = this.configuration.configuration_pos.electronic
        console.log(localStorage.getItem("plate_number"))
        console.log(this.configuration.configuration_pos.plate_number)
        console.log(this.electronic)
        if(localStorage.getItem("plate_number") == this.configuration.configuration_pos.plate_number || this.electronic == false){
            this.plate_number_valid = true
            await this.initForm();
            await this.getTables();
            this.events();
            await this.getFormPosLocalStorage()
            // await this.initCurrencyType()
            this.customer = await this.getLocalStorageIndex('customer')
            if (document.querySelector('.sidebar-toggle')) {
                document.querySelector('.sidebar-toggle').click()
            }
        }
        else
            this.plate_number_valid = false
    },

    computed: {
        classObjectCol() {

            let cols = this.configuration.colums_grid_item

            let clase = 'c3'
            switch (cols) {
                case 2:
                    clase = '6'

                    break;
                case 3:
                    clase = '4'

                    break;
                case 4:
                    clase = '3'

                    break;
                case 5:
                    clase = '2'

                    break;
                case 6:
                    clase = '2'
                    break;
                default:

            }
            return {
                [`col-md-${clase}`]: true
            }
        }
    },
    methods: {
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page
                    ? this.pagination.current_page
                    : 1,
                input_item: this.input_item,
                cat: this.category_selected,
                limit: this.limit
            });
        },
        getRecords() {
            this.loading = true;
            return this.$http
                .get(
                    `/${this.resource}/search_items?${this.getQueryParameters()}&cat=${
                        this.category_selected
                    }`
                )
                .then(response => {
                    this.all_items = response.data.data;
                    this.items = response.data.data;
                    this.filterItems();
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                    this.loading = false;
                    if (response.data.meta.total > 0) {
                        this.pagination.total = response.data.meta.total;
                    } else {
                        this.pagination.total = 0;
                    }
                });
        },
        setListPriceItem(item_unit_type, index) {

            let list_price = 0

            switch (item_unit_type.price_default) {
                case 1:
                    list_price = item_unit_type.price1
                    break
                case 2:
                    list_price = item_unit_type.price2
                    break
                case 3:
                    list_price = item_unit_type.price3
                    break
            }

            this.items[index].sale_unit_price = parseFloat(list_price)
            this.items[index].unit_type_id = item_unit_type.unit_type_id
            this.items[index].unit_type = item_unit_type.unit_type
            this.items[index].presentation = item_unit_type

            this.$message.success("Precio seleccionado")
        },
        filterCategorie(id, mod = false) {

            if (id) {
                this.category_selected = id;
                this.getRecords();
            } else {
                this.category_selected = "";
                this.getRecords();
            }

            if (mod) {
                this.place = 'cat2'
            } else {
                this.place = 'prod'
            }

        },
        getColor(i) {
            return this.colors[(i % this.colors.length)]
        },
        initCurrencyType() {
            this.currency = _.find(this.currencies, {
                'id': this.form.currency_id
            })
        },
        getFormPosLocalStorage() {
            let form_pos = localStorage.getItem('form_pos');
            form_pos = JSON.parse(form_pos)
            if (form_pos) {
                this.form = form_pos
                // this.calculateTotal()
            }

            if (!this.form.customer_id) {
                const customer_default = _.find(this.all_customers, {'number': '222222222222'}) ?? null
                if (customer_default) {
                    this.form.customer_id = customer_default.id
                    this.changeCustomer()
                }
            }

        },
        setFormPosLocalStorage(form_param = null) {

            if (form_param) {

                localStorage.setItem('form_pos', JSON.stringify(form_param));

            } else {

                localStorage.setItem('form_pos', JSON.stringify(this.form));
            }

        },
        cancelFormPosLocalStorage() {

            localStorage.setItem('form_pos', JSON.stringify(null));
            this.setLocalStorageIndex('customer', null)

        },
        clickOpenInputEditUP(index) {
            this.items[index].edit_unit_price = true
        },
        clickEditUnitPriceItem(index) {
            // console.log(index)
            let price_with_tax = this.items[index].edit_sale_unit_price //price with tax
            this.items[index].sale_unit_price_with_tax = price_with_tax
            this.items[index].sale_unit_price = price_with_tax / (1 + (this.items[index].tax.rate / this.items[index].tax.conversion))
            this.items[index].edit_unit_price = false

            // console.log(item_search)

        },
        clickCancelUnitPriceItem(index) {
            // console.log(index)
            this.items[index].edit_unit_price = false

        },
        clickWarehouseDetail(item) {
            this.unittypeDetail = item.unit_type
            this.warehousesDetail = item.warehouses
            this.showWarehousesDetail = true
        },
        clickHistoryPurchases(item_id) {

            this.history_item_id = item_id
            this.showDialogHistoryPurchases = true
            // console.log(item)
        },
        clickHistorySales(item_id) {
            if (!this.form.customer_id)
                return this.$message.error("Debe seleccionar el cliente")

            this.history_item_id = item_id
            this.showDialogHistorySales = true
            // console.log(item)
        },
        keyupEnterCustomer() {

            if (this.input_person.number) {

                if (!isNaN(parseInt(this.input_person.number))) {

                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = '1'
                            this.showDialogNewPerson = true
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                        default:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                    }
                }
            }
        },
        keyupCustomer(e) {

            if (e.key !== "Enter") {

                this.input_person.number = this.$refs.select_person.$el.getElementsByTagName('input')[0].value
                let exist_persons = this.all_customers.filter((customer) => {
                    let pos = customer.description.search(this.input_person.number);
                    return (pos > -1)
                })

                this.input_person.number = (exist_persons.length == 0) ? this.input_person.number : null

            }

        },
        calculateQuantity(index) {
            // console.log(this.form.items[index])
            if (this.form.items[index].item.calculate_quantity) {
                let quantity = _.round(
                    parseFloat(this.form.items[index].total) /
                    parseFloat(this.form.items[index].unit_price),
                    4
                );

                if (quantity) {
                    this.form.items[index].quantity = quantity;
                    this.form.items[index].item.aux_quantity = quantity;
                } else {
                    this.form.items[index].quantity = 0;
                    this.form.items[index].item.aux_quantity = 0;
                }
            }

        },

        changeCustomer() {
            let customer = _.find(this.all_customers, {
                id: this.form.customer_id
            });
            this.customer = customer;
            this.form.document_type_id = '90';
            this.setLocalStorageIndex('customer', this.customer)
            this.setFormPosLocalStorage()
        },

        getLocalStorageIndex(key, re_default = null) {

            let ls_obj = localStorage.getItem(key);
            ls_obj = JSON.parse(ls_obj)

            if (ls_obj) {
                return ls_obj
            }

            return re_default
        },
        setLocalStorageIndex(key, obj) {
            localStorage.setItem(key, JSON.stringify(obj));
        },
        async events() {

            await this.$eventHub.$on('initInputPerson', () => {
                this.initInputPerson()
            })

            await this.$eventHub.$on('eventSetFormPosLocalStorage', (form_param) => {
                this.setFormPosLocalStorage(form_param)
            })

            await this.$eventHub.$on("cancelSale", () => {
                this.is_payment = false;
                this.initForm();
                this.changeExchangeRate()
                this.cancelFormPosLocalStorage()
            });

            await this.$eventHub.$on("reloadDataPersons", customer_id => {
                this.reloadDataCustomers(customer_id);
                this.setFormPosLocalStorage()
            });

            await this.$eventHub.$on("reloadDataItems", item_id => {
                this.reloadDataItems(item_id);
            });

            await this.$eventHub.$on("saleSuccess", () => {
                // this.is_payment = false
                this.initForm();
                this.getTables();
                this.setFormPosLocalStorage()
                this.items_refund = []
            });
        },

        initForm() {
            this.form = {
                customer_id: null,
                document_type_id: '01',
                series_id: null,
                establishment_id: null,
                type_document_id: 1,
                currency_id: 170,
                date_issue: moment().format('YYYY-MM-DD'),
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                exchange_rate_sale: 0,
                date_expiration: null,
                type_invoice_id: 1,
                total_discount: 0,
                total_tax: 0,
                watch: false,
                subtotal: 0,
                items: [],
                taxes: [],
                total: 0,
                sale: 0,
                time_days_credit: 0,
                service_invoice: {},
                payment_form_id: 1,
                payment_method_id: 1,
                payments: [],
                electronic: false,
            }
            this.initFormItem();
            this.changeDateOfIssue();
            this.initInputPerson()
        },

        initInputPerson() {
            this.input_person = {
                number: '',
                identity_document_type_id: ''
            }
        },

        initFormItem() {
            this.form_item = {
                id: null,
                item_id: null,
                item: {},
                code: null,
                discount: 0,
                name: null,
                unit_price_value: 0,
                unit_price: 0,
                quantity: 1,
                aux_quantity: 1,
                subtotal: null,
                tax: {},
                tax_id: null,
                total: 0,
                total_tax: 0,
                edited_price: false,
                type_unit: {},
                unit_type_id: null,
                item_unit_types: [],
                IdLoteSelected: null,
                sale_unit_price_with_tax: 0,
                refund: false
            };
            //this.items_refund = []
        },

        async clickPayment() {

            let flag = 0;
            this.form.items.forEach(row => {
                if (row.aux_quantity < 0 || row.total < 0 || isNaN(row.total)) {
                    flag++;
                }
            });

            if (flag > 0)
                return this.$message.error("Cantidad negativa o incorrecta");
            if (!this.form.customer_id)
                return this.$message.error("Seleccione un cliente");
            if (!this.form.items[0])
                return this.$message.error("Seleccione un producto");

            this.form.establishment_id = this.establishment.id;
            this.loading = true;
            await this.sleep(800);
            this.is_payment = true;
            this.loading = false;

        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        clickDeleteCustomer() {
            this.form.customer_id = null;
            this.setFormPosLocalStorage()
        },
        async clickAddItem(item, index, input = false) {
            const presentation = item.presentation
            // console.log(item)
            if (this.type_refund) {
//                console.log("Aqui devolucion...")
                this.form_item.item = item;
                this.form_item.unit_price_value = this.form_item.item.sale_unit_price;
                this.form_item.quantity = 1;
                this.form_item.aux_quantity = 1;

                let unit_price = this.form_item.unit_price_value;

                this.form_item.unit_price = unit_price;
                this.form_item.item.unit_price = unit_price;
                this.form_item.item.presentation = null;

                // this.form_item.id = this.form_item.item.item_id
                this.form_item.item_id = this.form_item.item.item_id
                this.form_item.unit_type_id = this.form_item.item.unit_type_id
                this.form_item.tax_id = (this.taxes.length > 0) ? this.form_item.item.tax.id : null
                this.form_item.tax = _.find(this.taxes, {
                    'id': this.form_item.tax_id
                })
                this.form_item.unit_type = this.form_item.item.unit_type
                this.form_item.refund = true
                this.form_item.sale_unit_price_with_tax = -1 * item.sale_unit_price_with_tax
                this.items_refund.push(this.form_item);
                //item.aux_quantity = 1;
            } else {
                this.loading = true;
                // let exchangeRateSale = this.form.exchange_rate_sale;
                // let exist_item = _.find(this.form.items, {
                //     item_id: item.item_id
                // });

                let exist_item = null

                if(!presentation) {

                    exist_item = _.find(this.form.items, {
                        item_id: item.item_id,
                        unit_type_id: item.unit_type_id
                    })

                }else{

                    exist_item = _.find(this.form.items, {
                        item_id: item.item_id,
                        presentation: presentation,
                        unit_type_id: item.unit_type_id
                    })
                }


                let pos = this.form.items.indexOf(exist_item);
                let response = null;

                if (exist_item) {
                    item.edited_price = input
                    if (input) {
                        response = await this.getStatusStock(item.item_id, exist_item.item.aux_quantity);

                        if (!response.success) {
                            item.item.aux_quantity = item.quantity;
                            this.loading = false;
                            return this.$message.error(response.message);
                        }

                        exist_item.quantity = exist_item.item.aux_quantity;
                    } else {
                        response = await this.getStatusStock(item.item_id, parseFloat(exist_item.item.aux_quantity) + 1);

                        if (!response.success) {
                            this.loading = false;
                            return this.$message.error(response.message);
                        }

                        exist_item.quantity++;
                        exist_item.item.aux_quantity++;
                    }

                    let search_item_bd = await _.find(this.items, {
                        item_id: item.item_id
                    });

                    if (search_item_bd) {
                        exist_item.item.unit_price = parseFloat(search_item_bd.sale_unit_price)
                    }

                    let unit_price = exist_item.item.sale_unit_price
                    exist_item.item.unit_price = unit_price

                    exist_item.unit_type_id = item.unit_type_id

                    this.form.items[pos] = exist_item;

                } else {

                    response = await this.getStatusStock(item.item_id, 1);

                    if (!response.success) {
                        this.loading = false;
                        return this.$message.error(response.message);
                    }

                    this.form_item.item = { ...item }
                    // this.form_item.item = item;

                    this.form_item.unit_price_value = this.form_item.item.sale_unit_price;
                    this.form_item.quantity = 1;
                    this.form_item.aux_quantity = 1;

                    let unit_price = this.form_item.unit_price_value;

                    this.form_item.unit_price = unit_price;
                    this.form_item.item.unit_price = unit_price;
                    // this.form_item.item.presentation = null;

                    // this.form_item.id = this.form_item.item.item_id
                    this.form_item.item_id = this.form_item.item.item_id
                    this.form_item.tax_id = (this.taxes.length > 0) ? (this.form_item.item.tax !== null ? this.form_item.item.tax.id : null) : null
                    this.form_item.tax = _.find(this.taxes, {
                        'id': this.form_item.tax_id
                    })

                    // lista precios
                    if(presentation)
                    {
                        this.form_item.presentation = presentation
                        this.form_item.unit_type_id = presentation.unit_type_id
                        this.form_item.unit_type = presentation.unit_type

                    }else
                    {
                        this.form_item.presentation = null
                        this.form_item.unit_type_id = this.form_item.item.unit_type_id
                        this.form_item.unit_type = this.form_item.item.unit_type
                    }


                    this.form.items.push(this.form_item);
                    item.aux_quantity = 1;

                }

                if(!input)
    	                this.$notify({
                        title: "",
                        message: "Producto añadido!",
                        type: "success",
                        duration: 700
                    });

            }

            // console.log(this.form.items)
            await this.calculateTotal();
            this.loading = false;
            await this.setFormPosLocalStorage()
            await this.initFormItem()
        },
        async getStatusStock(item_id, quantity) {
            let data = {};
            if (!quantity) quantity = 0;
            await this.$http
                .get(`/${this.resource}/validate_stock/${item_id}/${quantity}`)
                .then(response => {
                    data = response.data;
                });
            return data;
        },
        async clickDeleteItem(index) {
            this.form.items.splice(index, 1);

            this.calculateTotal();

            await this.setFormPosLocalStorage()
        },
        async clickDeleteItemRefund(index) {
            this.items_refund.splice(index, 1);
            this.calculateTotal();
            await this.setFormPosLocalStorage()
        },

        calculateTotal() {
            this.setDataTotals()
        },
        changeDateOfIssue() {
            // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
            //     this.form.exchange_rate_sale = response
            // })

        },
        setDataTotals() {
            let val = this.form
            val.taxes = this.taxes;
            val.taxes.forEach(tax => {
                tax.total = 0
            });

            val.items.forEach(item => {
                item.tax = this.taxes.find(tax => tax.id == item.tax_id);

                if (
                    item.discount == null ||
                    item.discount == "" ||
                    item.discount > item.unit_price * item.quantity
                ) {
                    this.$set(item, "discount", 0);
                }

                if (item.tax != null) {

                    let tax = val.taxes.find(tax => tax.id == item.tax.id);
//                    tax.total = 0

                    if (item.tax.is_fixed_value)
                        item.total_tax = (
                            item.tax.rate * item.quantity -
                            (item.discount < item.unit_price * item.quantity ? item.discount : 0)
                        ).toFixed(2);

//                    console.log(item)
                    if (item.tax.is_percentage) {
                        if(!item.edited_price){
                            item.total_tax = (
                                (item.unit_price * item.quantity -
                                    (item.discount < item.unit_price * item.quantity ?
                                        item.discount :
                                        0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);
                        }
                        else{
//                            console.log("Aquui 2");
//                            console.log(item.unit_price)
//                            console.log(item.sale_unit_price_with_tax)
//                            console.log(item.tax.rate)
//                            console.log(item.tax.conversion)
//                            console.log(1 + (item.tax.rate / item.tax.conversion))
                            item.unit_price = (item.sale_unit_price_with_tax / (1 + (item.tax.rate / item.tax.conversion)))
//                            console.log(item.unit_price)
                            item.total_tax = (
                                (item.unit_price * item.quantity -
                                    (item.discount < item.sale_unit_price_with_tax * item.quantity ?
                                        item.discount :
                                        0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);
                        }
                    }

                    if (!tax.hasOwnProperty("total")) {
                        tax.total = Number(0).toFixed(2);
                    }
//                    console.log(tax.total)
//                    console.log(item.total_tax)
                    tax.total = (Number(tax.total) + Number(item.total_tax)).toFixed(2);
//                    console.log(tax.total)
                }
                if(!item.edited_price){
                    item.subtotal = (
                        Number(item.unit_price * item.quantity) + Number(item.total_tax)
                    ).toFixed(2);
                }
                else{
                    item.subtotal = (
                        Number(item.sale_unit_price_with_tax * item.quantity)
                    ).toFixed(2);
                }

                this.$set(
                    item,
                    "total",
                    (Number(item.subtotal) - Number(item.discount)).toFixed(2)
                );

                if(!item.edited_price){
                    this.$set(
                        item,
                        "sale_unit_price_with_tax",
                        (Number(item.subtotal) / Number(item.quantity)).toFixed(2)
                    );
                }
            });

            this.items_refund.forEach(item => {
                item.tax = this.taxes.find(tax => tax.id == item.tax_id);
                this.$set(item, "discount", 0);
                item.total_tax = 0;
                if (item.tax != null) {
                    let tax = val.taxes.find(tax => tax.id == item.tax.id);
                    if (item.tax.is_fixed_value) {
                        item.total_tax = (
                            item.tax.rate * item.quantity -
                            (item.discount < item.unit_price * item.quantity ? item.discount : 0)
                        ).toFixed(2);
                    }

                    if (item.tax.is_percentage) {
                        item.total_tax = (
                            (item.unit_price * item.quantity -
                                (item.discount < item.unit_price * item.quantity ?
                                    item.discount :
                                    0)) *
                            (item.tax.rate / item.tax.conversion)
                        ).toFixed(2);
                    }

                    if (!tax.hasOwnProperty("total")) {
                        tax.total = Number(0).toFixed(2);
                    }

                    tax.total = (Number(tax.total) - Number(item.total_tax)).toFixed(2);
                }

                item.subtotal = (
                    Number(item.unit_price * item.quantity) + Number(item.total_tax)
                ).toFixed(2);

                this.$set(
                    item,
                    "total",
                    ((Number(item.subtotal) - Number(item.discount))).toFixed(2)
                );

            })
            const subtotal = val.items.reduce((p, c) => Number(p) + (Number(c.subtotal) - Number(c.discount)), 0);
            const subtotal_refund = this.items_refund.reduce((p, c) => Number(p) + (Number(c.subtotal) - Number(c.discount)), 0);

            val.subtotal = (subtotal - subtotal_refund).toFixed(2)
//            console.log(val.items)
//            console.log(val.items.reduce((p, c) => Number(p) + ((Number(c.sale_unit_price_with_tax) *  Number(c.quantity))) - Number(c.total_tax) - Number(c.discount), 0))
            const sale = !val.items.edited_price ? val.items.reduce((p, c) => Number(p) + Number((c.sale_unit_price_with_tax * c.quantity) - c.total_tax) - Number(c.discount), 0) : val.items.reduce((p, c) => Number(p) + Number(c.unit_price * c.quantity) - Number(c.discount), 0);
//            console.log(this.items_refund)
//            console.log(!val.items.edited_price)

//            const sale_refund = !val.items.edited_price ? this.items_refund.reduce((p, c) => Number(p) - Number((c.sale_unit_price_with_tax  * c.quantity) + c.total_tax) - Number(c.discount), 0) : this.items_refund.reduce((p, c) => Number(p) - Number(c.unit_price * c.quantity) - Number(c.discount), 0);
//            const s1 = this.items_refund.reduce((p, c) => Number(p) - Number((c.sale_unit_price_with_tax  * c.quantity) + c.total_tax) - Number(c.discount), 0)
            const sale_refund = this.items_refund.reduce((p, c) => Number(p) - Number(c.unit_price * c.quantity) - Number(c.discount), 0)
//            console.log(sale)
//            console.log(sale_refund)
            val.sale = (sale + sale_refund).toFixed(2)
//            console.log(val.sale)
            val.total_discount = val.items
                .reduce((p, c) => Number(p) + Number(c.discount), 0)
                .toFixed(2);
//            console.log(val.items.reduce((p, c) => Number(p) + Number(c.total_tax), 0))
            val.total_tax = val.items
                .reduce((p, c) => Number(p) + Number(c.total_tax), 0)
                .toFixed(2);

            let total = val.items
                .reduce((p, c) => Number(p) + Number(c.total), 0);

            let total_refund = this.items_refund
                .reduce((p, c) => Number(p) + Number(c.total), 0);

            let totalRetentionBase = Number(0);

            // this.taxes.forEach(tax => {
            val.taxes.forEach(tax => {
                if (tax.is_retention && tax.in_base && tax.apply) {
                    tax.retention = (
                        Number(val.sale) *
                        (tax.rate / tax.conversion)
                    ).toFixed(2);

                    totalRetentionBase =
                        Number(totalRetentionBase) + Number(tax.retention);

                    if (Number(totalRetentionBase) >= Number(val.sale))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                    total -= Number(tax.retention).toFixed(2);
                }

                if (
                    tax.is_retention &&
                    !tax.in_base &&
                    tax.in_tax != null &&
                    tax.apply
                ) {
                    let row = val.taxes.find(row => row.id == tax.in_tax);

                    tax.retention = Number(
                        Number(row.total) * (tax.rate / tax.conversion)
                    ).toFixed(2);

                    if (Number(tax.retention) > Number(row.total))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                    row.retention = Number(tax.retention).toFixed(2);
                    total -= Number(tax.retention).toFixed(2);
                }
            });

            val.total = (Number(total) - Number(total_refund)).toFixed(2)

        },

        changeExchangeRate() {
            // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
            //     this.form.exchange_rate_sale = response
            // })
        },

        async getTables() {
            await this.$http.get(`/${this.resource}/tables`).then(response => {
                this.all_items = response.data.items;
                this.all_customers = response.data.customers;
                this.currencies = response.data.currencies;
                this.establishment = response.data.establishment;
                this.user = response.data.user;
                this.form.currency_id = this.currencies.length > 0 ? this.currencies[0].id : null;
                // console.log(this.form.currency_id)
                this.taxes = response.data.taxes
                this.renderCategories(response.data.categories)
                // this.currency = _.find(this.currencys, {'id': this.form.currency_id})
                // this.changeCurrencyType();
                this.initCurrencyType()
                this.filterItems();
                this.changeDateOfIssue();
                this.changeExchangeRate()
            });
//            console.log(this.electronic);
        },

        renderCategories(source) {
            const contex = this
            this.categories = source.map((obj, index) => {
                return {
                    id: obj.id,
                    name: obj.name,
                    color: contex.getColor(index)
                }
            })

            this.categories.unshift({
                id: null,
                name: 'Todos',
                color: '#2C8DE3'
            })
        },
        searchItems() {
            if (this.input_item.length > 3) {
                this.loading = true;
                let parameters = `input_item=${this.input_item}`;

                this.$http
                    .get(`/${this.resource}/search_items?${parameters}`)
                    .then(response => {
                        // console.log(response)
                        this.items = response.data.data;

                        this.pagination = response.data.meta;
                        this.pagination.per_page = parseInt(
                            response.data.meta.per_page
                        );

                        this.loading = false;
                        if (this.items.length == 0) {
                            this.filterItems();
                        }
                    });
            } else {
                // this.customers = []
                this.filterItems();
            }

        },
        async searchItemsBarcode() {

            // console.log(query)
            // console.log("in:" + this.input_item)

            if (this.input_item.length > 1) {

                this.loading = true;
                let parameters = `input_item=${this.input_item}`;

                await this.$http.get(`/${this.resource}/search_items?${parameters}`)
                    .then(response => {

                        this.items = response.data.data;

                        this.pagination = response.data.meta;
                        this.pagination.per_page = parseInt(
                            response.data.meta.per_page
                        );

                        this.enabledSearchItemsBarcode()
                        this.loading = false;
                        if (this.items.length == 0) {
                            this.filterItems();
                        }

                    });

            } else {

                await this.filterItems();

            }

        },
        enabledSearchItemsBarcode() {

            if (this.search_item_by_barcode) {
                console.log(this.items)
                if (this.items.length == 1) {

                    // console.log(this.items)
                    this.clickAddItem(this.items[0], 0);
                    this.filterItems();

                }

                this.cleanInput();

            }

        },
        changeSearchItemBarcode() {
            this.cleanInput()
        },
        cleanInput() {
            this.input_item = null;
        },
        filterItems() {
            this.items = this.all_items;
        },
        reloadDataCustomers(customer_id) {
            this.$http.get(`/${this.resource}/table/customers`).then(response => {
                this.all_customers = response.data;
                this.form.customer_id = customer_id;
                this.changeCustomer();
            });
        },
        reloadDataItems(item_id) {
            this.$http.get(`/${this.resource}/table/items`).then(response => {
                this.all_items = response.data;
                this.filterItems();
            });
        },
        selectCurrencyType() {
            // this.form.currency_id = (this.form.currency_id === 'PEN') ? 'USD':'PEN'
            // this.changeCurrencyType()
        },
        async changeCurrencyType() {

            // console.log(this.form.currency_id)
            // this.currency = await _.find(this.currencys, {'id': this.form.currency_id})
            // let items = []
            // this.form.items.forEach((row) => {
            //     items.push(calculateRowItem(row, this.form.currency_id, this.form.exchange_rate_sale))
            // });
            // this.form.items = items
            // this.calculateTotal()

            // await this.setFormPosLocalStorage()

        },
        openFullWindow() {
            location.href = `/${this.resource}/pos_full`
        },
        back() {
            this.place = 'cat'
        },
        setView() {
            this.place = 'cat2'
        },
        nameSets(id) {
            let row = this.items.find(x => x.item_id == id)
            if (row) {

                if (row.sets.length > 0) {
                    return row.sets.join('-')
                } else {
                    return ''
                }

            }
        }
    }
};
</script>
