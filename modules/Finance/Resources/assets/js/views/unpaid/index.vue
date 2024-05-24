<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Cuentas por cobrar</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">

                <div class="row">

                    <div class="col-xl-12">
                        <section >
                        <div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Establecimiento</label>
                                        <el-select v-model="form.establishment_id" @change="loadUnpaid">
                                            <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                        </el-select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">Periodo</label>
                                    <el-select v-model="form.period" @change="changePeriod">
                                        <el-option key="month" value="month" label="Por mes"></el-option>
                                        <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                                        <el-option key="date" value="date" label="Por fecha"></el-option>
                                        <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                                    </el-select>
                                </div>
                                <template v-if="form.period === 'month' || form.period === 'between_months'">
                                    <div class="col-md-3">
                                        <label class="control-label">Mes de</label>
                                        <el-date-picker v-model="form.month_start" type="month"
                                                        @change="changeDisabledMonths"
                                                        value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_months'">
                                    <div class="col-md-3">
                                        <label class="control-label">Mes al</label>
                                        <el-date-picker v-model="form.month_end" type="month"
                                                        @change="loadUnpaid"
                                                        :picker-options="pickerOptionsMonths"
                                                        value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'date' || form.period === 'between_dates'">
                                    <div class="col-md-3">
                                        <label class="control-label">Fecha del</label>
                                        <el-date-picker v-model="form.date_start" type="date"
                                                        @change="changeDisabledDates"
                                                        value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_dates'">
                                    <div class="col-md-3">
                                        <label class="control-label">Fecha al</label>
                                        <el-date-picker v-model="form.date_end" type="date"
                                                        :picker-options="pickerOptionsDates"
                                                        @change="loadUnpaid"
                                                        value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                                    </div>
                                </template>

                                <div class="col-md-6">
                                    <label class="control-label">Cliente</label>
                                    <el-select
                                        @change="changeCustomerUnpaid"
                                        @clear="loadUnpaid"
                                        filterable
                                        clearable
                                        v-model="form.customer_id"
                                        placeholder="Seleccionar cliente"
                                        >
                                        <el-option
                                            v-for="item in customers"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id"
                                        ></el-option>
                                    </el-select>
                                </div>

                                <div class="col-md-6" style="margin-top:29px">
<!--                                    <el-button
                                        class="submit"
                                        type="success"
                                        @click.prevent="clickOpen()"
                                        >
                                        <i class="fa fa-file-excel"></i> Exportar Todo
                                    </el-button>    -->

                                    <el-button
                                        v-if="records.length > 0"
                                        class="submit"
                                        type="success"
                                        @click.prevent="clickDownload('excel')"
                                        >
                                        <i class="fa fa-file-excel"></i> Exportar Excel
                                    </el-button>
                                </div>
                            </div>
                            <!-- <div class="row mt-5 mb-3 text-right">
                                <div class="col-md-1 text-right">
                                </div>

                                <div class="col-md-2 text-right">
                                    <el-badge :value="getTotalRowsUnpaid" class="item">
                                    <span size="small">Total comprobantes</span>
                                    </el-badge>
                                </div>
                                <div class="col-md-2 text-right">
                                    <el-badge :value="getTotalAmountUnpaid" class="item">
                                    <span size="small">Monto general (PEN)</span>
                                    </el-badge>
                                </div>
                                <div class="col-md-2 text-right">
                                    <el-badge :value="getCurrentBalance" class="item">
                                    <span size="small">Saldo corriente (PEN)</span>
                                    </el-badge>
                                </div>
                                <div class="col-md-2 text-right">
                                    <el-badge :value="getTotalAmountUnpaidUsd" class="item">
                                    <span size="small">Monto general (USD)</span>
                                    </el-badge>
                                </div>
                                <div class="col-md-2 text-right">
                                    <el-badge :value="getCurrentBalanceUsd" class="item">
                                    <span size="small">Saldo corriente (USD)</span>
                                    </el-badge>
                                </div>
                            </div> -->

                            <div class="table-responsive mt-4">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>F.Emisión</th>
                                    <th>F.Vencimiento</th>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Días de retraso</th>


                                    <th>Ver Cartera</th>
                                    <th>Moneda</th>
                                    <th class="text-right">Por cobrar</th>
                                    <th class="text-right">Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(row, index) in records">
                                        <tr v-if="row.total_to_pay > 0" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.date_of_issue }}</td>
                                            <td>{{ row.date_of_due ? row.date_of_due : 'No tiene fecha de vencimiento.'}}</td>
                                            <td>{{ row.number_full }}</td>
                                            <td>{{ row.customer_name }}</td>
                                            <td>{{ row.delay_payment ? row.delay_payment : 'No tiene días atrasados.' }}</td>

                                            <td>
                                                <el-popover placement="right" width="300" trigger="click">
                                                <p>
                                                    Saldo actual:
                                                    <span class="custom-badge">{{ row.total_to_pay }}</span>
                                                </p>
                                                <p>
                                                    Fecha ultimo pago:
                                                    <span
                                                    class="custom-badge"
                                                    >{{ row.date_payment_last ? row.date_payment_last : 'No registra pagos.' }}</span>
                                                </p>

                                                <el-button icon="el-icon-view" slot="reference"></el-button>
                                                </el-popover>
                                            </td>
                                                <td>{{row.currency_id}}</td>
                                            <td class="text-right text-danger">{{ row.total_to_pay }}</td>
                                            <td class="text-right">{{ row.total }}</td>
                                            <td class="text-right">
                                                <template v-if="row.type === 'document'">
                                                <button
                                                    type="button"
                                                    style="min-width: 41px"
                                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                    @click.prevent="clickDocumentPayment(row.id)"
                                                >Pagos</button>
                                                </template>

                                                <template v-else-if="row.type === 'remission'">
                                                    <button
                                                        type="button"
                                                        style="min-width: 41px"
                                                        class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                        @click.prevent="clickRemissionPayment(row.id)"
                                                    >
                                                        Pagos
                                                    </button>
                                                </template>

                                                <template v-else>
                                                <button
                                                    type="button"
                                                    style="min-width: 41px"
                                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                    @click.prevent="clickSaleNotePayment(row.id)"
                                                >Pagos</button>
                                                </template>

                                                <!-- <button
                                                type="button"
                                                style="min-width: 41px"
                                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                                @click.prevent="clickDocumentPayment(row.id)"
                                                >Detalle</button> -->
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <document-payments
            :showDialog.sync="showDialogDocumentPayments"
            :documentId="recordId"
            :external="true"
            ></document-payments>

        <sale-note-payments
            :showDialog.sync="showDialogSaleNotePayments"
            :documentId="recordId"
            :external="true"
            ></sale-note-payments>

        <remission-payments
            :showDialog.sync="showDialogRemissionPayments"
            :remissionId="recordId"
            :external="true"
            ></remission-payments>
    </div>
</template>

<script>

    import DocumentPayments from "@views/documents/partials/payments.vue";
    import SaleNotePayments from "@views/sale_notes/partials/payments.vue";
    import DataTable from '../../components/DataTableWithoutPaging.vue'
    import queryString from "query-string";
    import RemissionPayments from "@viewsModuleSale/co-remissions/partials/payments.vue";

    export default {
        components: {DocumentPayments, SaleNotePayments, DataTable, RemissionPayments},
        data() {
            return {
                resource: 'finances/unpaid',
                form: {},
                customers: [],
                recordId: null,
                records:[],
                establishments: [],
                pickerOptionsDates: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.date_start > time
                    }
                },
                pickerOptionsMonths: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM')
                        return this.form.month_start > time
                    }
                },
                showDialogDocumentPayments: false,
                showDialogSaleNotePayments: false,
                showDialogRemissionPayments: false,
            }
        },

        async created() {
            this.$eventHub.$on("reloadDataUnpaid", () => {
                this.loadUnpaid();
            });
            await this.initForm()
            await this.filter()
            await this.changePeriod()
        },

        computed: {
            getCurrentBalance() {
                const self = this;
                let source = [];
                if (self.form.customer_id) {
                    source = _.filter(self.records, function(item) {
                    return (
                        item.total_to_pay > 0 && item.customer_id == self.form.customer_id && item.currency_type_id == 'PEN'
                    );
                    });
                } else {
                    source = _.filter(this.records, function(item) {
                    return item.total_to_pay > 0 && item.currency_type_id == 'PEN';
                    });
                }

                return _.sumBy(source, function(item) {
                    return parseFloat(item.total_to_pay);
                }).toFixed(2);
            },

            getCurrentBalanceUsd() {
                const self = this;
                let source = [];
                if (self.form.customer_id) {
                    source = _.filter(self.records, function(item) {
                    return (
                        item.total_to_pay > 0 && item.customer_id == self.form.customer_id && item.currency_type_id == 'USD'
                    );
                    });
                } else {
                    source = _.filter(this.records, function(item) {
                    return item.total_to_pay > 0 && item.currency_type_id == 'USD';
                    });
                }

                return _.sumBy(source, function(item) {
                    return  parseFloat(item.total_to_pay);
                }).toFixed(2);
            },

            getTotalRowsUnpaid() {
                const self = this;

                if (self.form.customer_id) {
                    return _.filter(self.records, function(item) {
                    return (
                        item.total_to_pay > 0 && item.customer_id == self.form.customer_id
                    );
                    }).length;
                } else {
                    return _.filter(this.records, function(item) {
                    return item.total_to_pay > 0;
                    }).length;
                }
            },

            getTotalAmountUnpaid() {
                const self = this;
                let source = [];
                if (self.form.customer_id) {
                    source = _.filter(self.records, function(item) {
                    return (
                        item.total_to_pay > 0 && item.customer_id == self.form.customer_id && item.currency_type_id == 'PEN'
                    );
                    });
                } else {
                    source = _.filter(this.records, function(item) {
                    return item.total_to_pay > 0 &&  item.currency_type_id == 'PEN';
                    });
                }

                return _.sumBy(source, function(item) {
                    return  parseFloat(item.total)
                }).toFixed(2)
            },

            getTotalAmountUnpaidUsd() {
                const self = this;
                let source = [];
                if (self.form.customer_id) {
                    source = _.filter(self.records, function(item) {
                    return (
                        item.total_to_pay > 0 && item.customer_id == self.form.customer_id && item.currency_type_id == 'USD'
                    );
                    });
                } else {
                    source = _.filter(this.records, function(item) {
                    return item.total_to_pay > 0 && item.currency_type_id == 'USD';
                    });
                }

                return _.sumBy(source, function(item) {
                    return  parseFloat(item.total);
                }).toFixed(2)
            }
        },

        methods: {
            initForm() {
                this.form = {
                    establishment_id: null,
                    period: 'between_dates',
                    date_start: moment().format('YYYY-MM-DD'),
                    date_end: moment().format('YYYY-MM-DD'),
                    month_start: moment().format('YYYY-MM'),
                    month_end: moment().format('YYYY-MM'),
                    customer_id: null
                };
                this.loadUnpaid();
            },

            filter() {
                this.$http.get(`/${this.resource}/filter`, this.form).then(response => {
                    this.establishments = response.data.establishments;
                    this.customers = response.data.customers;
                    this.form.establishment_id = this.establishments.length > 0 ? this.establishments[0].id : null;
                });
            },

            loadUnpaid() {
//                if(this.form.customer_id){
                    this.$http.post(`/${this.resource}/records`, this.form).then(response => {
                        this.records = response.data.records;
                        //this.records_base = response.data.records;
                    });
//                }
            },

            clickRemissionPayment(recordId) {
                this.recordId = recordId;
                this.showDialogRemissionPayments = true;
            },
            clickDocumentPayment(recordId) {
                this.recordId = recordId;
                this.showDialogDocumentPayments = true;
            },
            clickSaleNotePayment(recordId) {
                this.recordId = recordId;
                this.showDialogSaleNotePayments = true;
            },

            clickDownloadDispatch(download) {
                window.open(download, "_blank");
            },

            clickDownload(type) {
                let query = queryString.stringify({
                    ...this.form
                });
                window.open(`/reports/no_paid/${type}/?${query}`, "_blank");
            },
            clickOpen(){
                window.open(`/${this.resource}/unpaidall`, "_blank");
            },

            changeCustomerUnpaid() {
                if (this.form.customer_id) {
                    this.loadUnpaid()
                    /*this.records = _.filter(this.records_base, {
                    customer_id: this.selected_customer
                    });*/
                } else {
                    this.records = []
                }
            },

            changeDisabledDates() {
                if (this.form.date_end < this.form.date_start) {
                    this.form.date_end = this.form.date_start
                }
                this.loadUnpaid();
            },
            changeDisabledMonths() {
                if (this.form.month_end < this.form.month_start) {
                    this.form.month_end = this.form.month_start
                }
                this.loadUnpaid();
            },
            changePeriod() {
                if(this.form.period === 'month') {
                    this.form.month_start = moment().format('YYYY-MM');
                    this.form.month_end = moment().format('YYYY-MM');
                }
                if(this.form.period === 'between_months') {
                    this.form.month_start = '2020-01';
//                    this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                    this.form.month_end = moment().endOf('year').format('YYYY-MM');;
                }
                if(this.form.period === 'date') {
                    this.form.date_start = moment().format('YYYY-MM-DD');
                    this.form.date_end = moment().format('YYYY-MM-DD');
                }
                if(this.form.period === 'between_dates') {
                    this.form.date_start = '2020-01-01';
//                    this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                    this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
                }
                this.loadUnpaid();
            },

        }
    }
</script>
