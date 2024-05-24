<template>
    <main class="content">
        <div class="container-fluid">
            <v-app id="taxes">
                <div class="content-header">
                    <h1>Reporte impuestos</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <v-btn icon class="bee darker text-white no-decoration" @click="downloadExport('report_tax')">
                                    <v-icon small>fas fa-file-download</v-icon>
                                </v-btn>
                            </li>
                        </ol>
                    </nav>
                </div>
                <v-card>
                    <v-list three-line subheader>
                        <v-form data-vv-scope="report_tax">
                            <v-container>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12 md6 lg4>
                                        <v-menu ref="menu_date_from" v-model="menuDateFrom" :close-on-content-click="false" :nudge-right="40" :return-value.sync="report.date_from" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{on}">
                                                <v-text-field v-model="report.date_from" v-validate="'required'" :error-messages="errors.collect('report_tax.date_from')" data-vv-name="date_from" label="Fecha desde *" prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker v-model="report.date_from" locale="es-co" :max="toDate" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menuDateFrom = false">Cancelar</v-btn>
                                                <v-btn flat color="primary" @click="$refs.menu_date_from.save(report.date_from)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs12 sm12 md6 lg4>
                                        <v-menu ref="menu_date_up" v-model="menuDateUp" :close-on-content-click="false" :nudge-right="40" :return-value.sync="report.date_up" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{on}">
                                                <v-text-field v-model="report.date_up" v-validate="'required'" :error-messages="errors.collect('report_tax.date_up')" data-vv-name="date_up" label="Fecha hasta *" prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker v-model="report.date_up" locale="es-co" :max="toDate" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menuDateUp = false">Cancelar</v-btn>
                                                <v-btn flat color="primary" @click="$refs.menu_date_up.save(report.date_up)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs12 sm12 md6 lg4>
                                        <v-btn color="success" :loading="loading" @click="validate('report_tax')">Consultar</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-list>
                    <v-data-table v-if="query" :headers="columnsTitles" :items="documents" :loading="loadDataTable" :rows-per-page-items="[100, 500, 1000]" class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{props.item.id}}</td>
                            <td>{{props.item.date_issue}}</td>
                            <td>{{props.item.client.name}}</td>
                            <td>
                                <div>{{props.item.type_document.name}}</div>
                                <div>{{props.item.prefix}}{{props.item.number}}<template v-if="props.item.type_document_id != 1">({{props.item.reference.prefix}}{{props.item.reference.number}})</template></div>
                            </td>
                            <td>{{ratePrefix()}}{{props.item.sale | numberFormat}}</td>
                            <td>{{ratePrefix()}}{{props.item.total_discount | numberFormat}}</td>
                            <td v-for="tax in taxTitles">{{ratePrefix()}}{{getTaxTotalBill(tax, props.item.taxes) | numberFormat}}</td>
                        </template>
                        <template v-slot:footer>
                            <td class="text-center" :colspan="(columns.length - 2)"><strong>Totales:</strong></td>
                            <td><strong>{{ratePrefix()}}{{getSaleTotal() | numberFormat}}</strong></td>
                            <td><strong>{{ratePrefix()}}{{getTotalDiscount() | numberFormat}}</strong></td>
                            <td v-for="tax in taxTitles"><strong>{{ratePrefix()}}{{getTaxTotal(tax) | numberFormat}}</strong></td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-app>
        </div>
    </main>
</template>

<script>
    import Helper from '../../../../mixins/Helper';
    
    export default {
        mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },
        data: () => ({
            loadDataTable: false,
            menuDateFrom: false,
            menuDateUp: false,
            loading: false,
            documents: [],
            taxTitles: [],
            columns: [{
                text: '#',
                value: 'id'
            }, {
                text: 'FECHA DE EMISIÓN',
                value: 'date_issue'
            }, {
                text: 'CLIENTE',
                value: 'client.name'
            }, {
                text: 'DOCUMENTO',
                value: 'number'
            }, {
                text: 'BASE',
                value: 'sale'
            }, {
                text: 'DESCUENTO',
                value: 'total_discount'
            }],
            taxesAll: [],
            query: false,
            report: {}
        }),
        computed: {
            columnsTitles() {
                let titles = [];
                
                this.columns.forEach(column => titles.push(column));
                
                this.taxTitles.forEach(tax => titles.push({
                    text: `${tax.name} (${tax.rate})`,
                    value: null
                }));
                
                return titles;
            }
        },
        mounted() {},
        methods: {
            validate(scope) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loading = true;
                        
                        axios.post(`${this.route}`, this.report).then(response => {
                            this.$setLaravelMessage(response.data);
                            
                            if (response.data.success) {
                                this.query = true;
                                this.documents = response.data.documents;
                                this.taxTitles = _.toArray(response.data.taxTitles);
                                this.taxesAll = response.data.taxesAll;
                            }
                        }).catch(error => {
                            this.query = false;
                            
                            this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            this.$setLaravelErrors(error.response.data);
                        }).then(() => {
                            this.loading = false;
                        });
                    }
                });
            },
            getTaxTotalBill(tax, taxes) {
                try {
                    let exist = taxes.find(row => row.id == tax.id);
                    
                    return exist.is_retention ? exist.retention : exist.total;
                }
                catch(e) {
                    console.log(e);
                    
                    return 0;
                }
            },
            getSaleTotal() {
                try {
                    return _.reduce(this.documents, (sum, value) => {
                        return Number(sum) + Number(value.sale);
                    }, 0);
                }
                catch(e) {
                    console.log(e);
                    
                    return 0;
                }
            },
            getTotalDiscount() {
                try {
                    return _.reduce(this.documents, (sum, value) => {
                        return Number(sum) + Number(value.total_discount);
                    }, 0);
                }
                catch(e) {
                    console.log(e);
                    
                    return 0;
                }
            },
            getTaxTotal(tax) {
                try {
                    return _.reduce(this.taxesAll.filter(row => row.id == tax.id), (sum, value) => {
                        return tax.is_retention ? (Number(sum) + Number(value.retention)) : (Number(sum) + Number(value.total));
                    }, 0);
                }
                catch (e) {
                    console.log(e);
                    
                    return 0;
                }
            },
            downloadExport(scope) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) window.open(`${this.route}/export?date_from=${this.report.date_from}&date_up=${this.report.date_up}`, '_blank');
                });
            }
        }
    }
</script>
