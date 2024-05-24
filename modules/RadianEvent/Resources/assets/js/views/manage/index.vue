<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Documentos recibidos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
            </div>
        </div>
        <div class="card mb-0" v-loading="loading">
            <div class="card-header bg-info">
                <h3 class="my-0">Eventos RADIAN - Documentos recibidos</h3>
            </div>
            <div class="card-body">
                
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Estado Actual</th>


                        <th>Tipo Documento</th>
                        <th>Fecha</th>
                        <th>Nit Empresa</th>
                        <th>Nombre</th>
                        <th>Prefijo</th>
                        <th>Numero</th>
                        <th>Impuestos</th>
                        <th>Vr. Documento</th>
                        <th>Attached Document</th>
                        <th>PDF</th>
                        
                        <th>Acuse Recibo</th>
                        <th>Recepcion Bienes</th>
                        <th>Aceptacion Expresa</th>
                        <th>Rechazo</th>

                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>

                        <td>
                            <template v-if="row.aceptacion == 1">
                                <i class="fa fa-circle" style="color: green"></i>
                            </template>
                            <template v-else>
                                <template v-if="row.rechazo == 1">
                                    <i class="fa fa-circle" style="color: red"></i>
                                </template>
                                <template v-else>
                                    
                                    <template v-if="row.rec_bienes == 1">
                                        <i class="fa fa-circle" style="color: yellow"></i>
                                    </template>
                                    <template v-else>
                                        <template v-if="row.acu_recibo == 1">
                                            <i class="fa fa-circle" style="color: blue"></i>
                                        </template>
                                        <template v-else>
                                            <i class="fa fa-circle" style="color: black"></i>
                                        </template>
                                    </template>
                                </template>
                            </template>
                        </td>

                        <td>{{ row.type_document_name }}</td>

                        <td>{{ row.date_issue }}</td>
                        <td>{{ row.identification_number }}</td>
                        <td>{{ row.name_seller }}</td>
                        <td>{{ row.prefix }}</td>
                        <td>{{ row.number }}</td>
                        <td>{{ row.total_tax }}</td>
                        <td>{{ row.total }}</td>
                        <td>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickDownload(row.xml)">
                                <i class="fas fa-download"></i>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDownload(row.pdf)">
                                <i class="fas fa-download"></i>
                            </button>
                        </td>
                        
                        <td> 
                            <!-- Acuse Recibo -->

                            <el-tooltip class="item" effect="dark" content="Documento electrónico por el cual el Adquiriente manifiesta que ha recibido la factura electrónica, de conformidad con el artículo 774 del Código de Comercio." placement="bottom-end">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickSendEvent(row.id, '1')">
                                    <i class="fas fa-file-import"></i>
                                </button>
                            </el-tooltip>

                            <!-- Acuse Recibo -->

                        </td>
                        <td> 
                            
                            <!-- Recepcion Bienes -->

                            <el-tooltip class="item" effect="dark" content="Documento electrónico por el cual el Adquiriente informa del recibo de los bienes o servicios adquiridos, de conformidad con el artículo 773 del Código de Comercio y en concordancia con el parágrafo 1 del artículo 2.2.2.53.4. del Decreto 1074 de 2015 Único Reglamentario del Sector Comercio, Industria y Turismo." placement="bottom-end">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickSendEvent(row.id, '3')">
                                    <i class="fas fa-file-import"></i>
                                </button>
                            </el-tooltip>

                            <!-- Recepcion Bienes -->

                        </td>
                        <td> 
                            <!-- Aceptacion Expresa -->

                            <el-tooltip class="item" effect="dark" content="Documento electrónico por el cual el Adquiriente informa al Emisor que acepta expresamente el Documento Electrónico que origina este tipo de ApplicationResponse de conformidad con el artículo 773 del Código de Comercio y en concordancia con el numeral 1 del artículo 2.2.2.53.4. del Decreto 1074 de 2015, Único Reglamentario del Sector Comercio, Industria y Turismo." placement="bottom-end">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickSendEvent(row.id, '4')">
                                    <i class="fas fa-file-import"></i>
                                </button>
                            </el-tooltip>

                            <!-- Aceptacion Expresa -->
                        </td>
                        <td> 
                            <!-- Rechazo -->
                            
                            <el-tooltip class="item" effect="dark" content="Documento electrónico mediante el cual el Adquiriente manifiesta que no acepta el documento de conformidad con el artículo 773 del Código de Comercio y en concordancia con el artículo 2.2.2.53.4. del Decreto 1074 de 2015, Único Reglamentario del Sector Comercio, Industria y Turismo. Este documento es para desaveniencias de tipo comercial, dado que el documento sobre el cual manifiesta el desacuerdo fue efectivamente Validado por la DIAN, en el sistema de Validación Previa, Nota: Se debe solicitar una nota contable al emisor." placement="bottom-end">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickRejected(row)">
                                    <i class="fas fa-file-import"></i>
                                </button>
                            </el-tooltip>

                            <!-- Rechazo -->
                        </td>

                    </tr>
                </data-table>

            </div>
        </div>

        <rejected-form :showDialog.sync="showDialogRejected"
                            :record="record"></rejected-form>
    </div>
</template>
<script>

    import DataTable from '@components/DataTable.vue'
    import RejectedForm from './partials/rejected.vue'

    export default {
        components: {DataTable, RejectedForm},
        data() {
            return {
                resource: 'co-radian-events',
                loading: false,
                showDialogRejected: false,
                record: null,
            }
        },
        created() {
        },
        methods: {
            clickDownload(filename) {
                window.open(`/${this.resource}/download/${filename}`, '_blank');
            },
            clickRejected(record)
            {
                this.record = record
                this.showDialogRejected = true
            },
            clickSendEvent(id, event_code)
            {
                this.loading = true

                const form = {
                    id: id,
                    event_code: event_code,
                }

                this.$http.post(`/${this.resource}/run-event`, form)
                    .then(response => {
                        console.log(response)
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        // if (error.response.status === 422) {
                        //     this.errors = error.response.data
                        // } else {
                            console.log(error)
                        // }
                    })
                    .then(() => {
                        this.loading = false
                    })
            }
        }
    }
</script>
