<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Documentos POS</span></li>
            </ol>
            <div class="right-wrapper pull-right">
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body pt-0">
                <div class="row p-2 justify-content-end">
                    <el-dropdown :hide-on-click="false">
                        <el-button type="primary">
                            Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item v-for="(column, index) in columns" :key="index">
                                <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Nota de Venta</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">POS Electronico</th>
                        <th class="text-center" v-if="columns.total_paid.visible">Pagado</th>
                        <th class="text-center" v-if="columns.total_pending_paid.visible">Por pagar</th>
                        <th class="text-center">Estado pago</th>
                        <th class="text-center">Descarga</th>
                         <th class="text-center" v-if="columns.type_period.visible" >
                            Tipo Periodo
                        </th>
                        <th class="text-center" v-if="columns.quantity_period.visible" >
                            Cantidad Periodo
                        </th>
                        <th class="text-center" v-if="columns.paid.visible">
                            Estado de Pago
                        </th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small><br/></td>
                        <td>{{ row.full_number }}
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white" :class="{'bg-danger': (row.state_type_id === '11'), 'bg-warning': (row.state_type_id === '13'), 'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}">{{row.state_type_description}}</span>
                        </td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-center">
                            <span class="badge text-white" :class="{ 'bg-success': (row.electronic), 'bg-primary' : (!row.electronic) }">{{ row.electronic ? 'Electronico' : 'Ticket Papel' }}</span>
                        </td>
                        <td class="text-center" v-if="columns.total_paid.visible">
                            {{row.total_paid}}
                        </td>
                        <td class="text-center" v-if="columns.total_pending_paid.visible">
                            {{row.total_pending_paid}}
                        </td>
                        <td class="text-center">
                            <span class="badge text-white" :class="{'bg-success': (row.paid), 'bg-warning': (!row.paid)}">{{row.paid ? 'Pagado':'Pendiente'}}</span>
                        </td>
                        <!--<td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickPayment(row.id)" ><i class="fas fa-money-bill-alt"></i></button>
                        </td>-->
                        <td class="text-center">
                            <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button> -->
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)"><i class="fas fa-file-pdf"></i></button>
                        </td>
                        <td class="text-right" v-if="columns.type_period.visible">
                            {{ row.type_period | period}}
                        </td>
                        <td class="text-right" v-if="columns.quantity_period.visible">
                            {{row.quantity_period}}
                        </td>
                        <td class="text-right" v-if="columns.paid.visible" >
                            {{row.paid ? 'Pagado' : 'Pendiente'}}
                        </td>
                        <td class="text-right">
                            <!-- <button data-toggle="tooltip" data-placement="top" title="Anular" v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                            @click.prevent="clickVoided(row.id)"><i class="fas fa-trash"></i></button>-->
                            <button  data-toggle="tooltip" data-placement="top" title="Imprimir" v-if="row.state_type_id != '11'"  type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)"><i class="fas fa-print"></i></button>
                            <button v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger m-1__2"
                                    @click.prevent="clickVoided(row.id)"
                                    >Anular</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>


        <sale-notes-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId"
                          :showClose="true"></sale-notes-options>


    </div>
</template>

<script>

    import DataTable from '../../../components/DataTableDocumentsPos.vue'
    //import SaleNotePayments from './partials/payments.vue'
    import SaleNotesOptions from './partials/document_pos_options.vue'
    //import SaleNoteGenerate from './partials/option_documents'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['soapCompany'],
        mixins: [deletable],
        components: {DataTable, SaleNotesOptions},
        data() {
            return {
                resource: 'document-pos',
                plate_number: "",
                showDialogPayments: false,
                showDialogOptions: false,
                showDialogGenerate: false,
                saleNotesNewId: null,
                recordId: null,
                showDialogOptions: false,
                columns: {
                    paid: {
                        title: 'Estado de Pago',
                        visible: false
                    },
                    type_period: {
                        title: 'Tipo Periodo',
                        visible: false
                    },
                    quantity_period: {
                        title: 'Cantidad Periodo',
                        visible: false
                    },
                    license_plate:{
                        title: 'Placa',
                        visible: false
                    },
                    total_paid:{
                        title: 'Pagado',
                        visible: false
                    },
                    total_pending_paid:{
                        title: 'Por pagar',
                        visible: false
                    }

                }
            }
        },
        created() {
        },
        filters:{
            period(name)
            {
                let res = ''
                switch(name)
                {
                    case 'month':
                        res = 'Mensual'
                        break
                    case 'year':
                        res = 'Anual'
                        break
                    default:

                        break;
                }

                return res
            }
        },

        async mounted(){
            this.cash_serial_number = localStorage.getItem('plate_number');
        },

        methods: {
            clickDownload(external_id) {
                window.open(`/document-pos/downloadExternal/${external_id}`, '_blank');
            },
            clickOptions(recordId) {
                this.saleNotesNewId = recordId
                this.showDialogOptions = true
            },
            clickGenerate(recordId) {
                this.recordId = recordId
                this.showDialogGenerate = true
            },
            clickPayment(recordId) {
                this.recordId = recordId;
                this.showDialogPayments = true;
            },
            clickCreate(id = '') {
                location.href = `/${this.resource}/create/${id}`
            },

            changeConcurrency(row) {

                // console.log(row)
                this.$http.post(`/${this.resource}/enabled-concurrency`, row).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit('reloadData')
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                });
            },
            clickVoided(id) {
                this.anular(`/${this.resource}/anulate/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickRefund(id)
            {
                location.href = `/${this.resource}/refund/${id}`
            }

        }
    }
</script>
