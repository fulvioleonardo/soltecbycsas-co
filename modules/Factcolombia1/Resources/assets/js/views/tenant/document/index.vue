<template>
    <div v-loading="loading">
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span></span> </li>
                <!-- <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</span></li> -->
            </ol>
            <div class="right-wrapper pull-right" >
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
                <el-button class="btn btn-custom btn-sm  mt-2 mr-2" :loading="Sincronizing" @click.prevent="clickSincronize()"><i class="fas fa-sync-alt" ></i> Sincronizar Envios API</el-button>
                <el-button class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-arrows-alt" ></i> Carga Masiva</el-button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <!-- <el-button class="submit" type="success" @click.prevent="clickDownloadReportPagos('excel')"><i class="fa fa-file-excel" ></i>  Descargar Pagos</el-button> -->
                <!-- <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown> -->
            </div>
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Estado</th>
                        <!-- <th>Acuse recibido cliente</th> -->
                        <th class="text-center">Moneda</th>
                        <th class="text-right">T.Venta</th>
                        <th class="text-right">T.Descuentos</th>
                        <th class="text-right">T.Impuestos</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Total</th>
                        <th class="text-center"></th>
                        <th class="text-right">Descargas</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" >
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.number_full }}<br/>
                            <small v-text="row.type_document_name"></small><br/>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary text-white" :class="{'bg-secondary': (row.state_document_id === 1), 'bg-success': (row.state_document_id === 5), 'bg-danger': (row.state_document_id === 6)}">
                                {{ row.state_document_name }}
                            </span>
                        </td>
                        <!-- <td class="text-center">{{ row.acknowledgment_received }}</td> -->
                        <td class="text-center">{{ row.currency_name }}</td>

                        <td class="text-right">{{ row.sale }}</td>
                        <td class="text-right">{{ row.total_discount }}</td>
                        <td class="text-right">{{ row.total_tax }}</td>
                        <td class="text-right">{{ row.subtotal }}</td>
                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickPayment(row.id)">Pagos</button>
                        </td>
                        <td class="text-right" >
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_xml)"
                                   >XML</button>
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                   >PDF</button>

                        </td>
                        <td class="text-right" >
                            <template v-if="row.btn_query">
                                <el-tooltip class="item" effect="dark" content="Consultar ZIPKEY a la DIAN" placement="top-start">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickQueryZipKey(row.id)">Consultar</button>
                                </el-tooltip>
                            </template>

                            <template v-if="(row.type_document_name=='Factura de Venta Nacional' || row.type_document_name=='Factura de Exportación' || row.type_document_name=='Factura de Contingencia' || row.type_document_name=='Factura electrónica de Venta - tipo 04') && (row.state_document_id==5 || row.state_document_id==1)">
                                <a :href="`/${resource}/note/${row.id}`" class="btn waves-effect waves-light btn-xs btn-warning m-1__2">Nota</a>
                            </template>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickOptions(row.id)">Opciones</button>

                            <template v-if="(row.type_document_name=='Factura de Venta Nacional' || row.type_document_name=='Factura de Exportación' || row.type_document_name=='Factura de Contingencia' || row.type_document_name=='Factura electrónica de Venta - tipo 04') && row.state_document_id!=6">
                                <a :href="`/${resource}/duplicate-invoice/${row.id}`" class="btn waves-effect waves-light btn-xs btn-info m-1__2">Duplicar</a>
                            </template>

                            <template v-if="row.state_document_id==6">
                                <a :href="`/${resource}/edit-invoice/${row.id}`" class="btn waves-effect waves-light btn-xs btn-info m-1__2">Editar</a>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <document-import :showDialog.sync="showImportDialog"></document-import>

            <document-payments :showDialog.sync="showDialogPayments"
                               :documentId="recordId"></document-payments>

            <document-options :showDialog.sync="showDialogOptions"
                              :showDownload="false"
                              :recordId="recordId"
                              :showClose="true"></document-options>
        </div>
    </div>
</template>

<script>
    import DataTable from '@components/DataTable.vue'
    import DocumentOptions from './partials/options.vue'
    import DocumentPayments from './partials/payments.vue'
    import DocumentImport from './partials/import.vue'

    export default {
        components: {DataTable, DocumentOptions, DocumentPayments, DocumentImport},
        data() {
            return {
                showDialogReportPayment:false,
                showDialogVoided: false,
                showImportDialog: false,
                showDialogCDetraction: false,
                showImportSecondDialog: false,
                resource: 'co-documents',
                recordId: null,
                showDialogOptions: false,
                showDialogPayments: false,
                loading: false,
                Sincronizing: false,
            }
        },
        created() {
        },
        methods: {
            async clickQueryZipKey(recordId) {

                this.loading = true

                await this.$http.post(`/${this.resource}/query-zipkey`, {
                    id : recordId
                }).then(response => {
                    // console.log(response)

                    if (response.data.success) {
                        this.$message.success(response.data.message)
                    }
                    else {
                        this.$message.error(response.data.message)
                    }

                    this.$eventHub.$emit('reloadData')

                }).catch(error => {

                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    }
                    else {
                        this.$message.error(error.response.data.message)
                    }


                }).then(() => {
                    this.loading = false
                })
            },

            clickImport() {
                this.showImportDialog = true;
            },

            async clickSincronize() {
                this.Sincronizing = true

                await this.$http.post(`/${this.resource}/sincronize`).then(response => {
                    // console.log(response)
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                    }
                    else {
                        this.$message.error(response.data.message)
                    }
                    this.$eventHub.$emit('reloadData')
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    }
                    else {
                        this.$message.error(error.response.data.message)
                    }
                }).then(() => {
                    this.Sincronizing = false
                })
            },

            clickPayment(recordId) {
                this.recordId = recordId;
                this.showDialogPayments = true;
            },
            clickVoided(recordId = null) {
                this.recordId = recordId
                this.showDialogVoided = true
            },
            clickDownload(download) {
                console.log(download)
                console.log(this.downloadFilename(download))
                this.$http.get(`/${this.resource}/downloadFile/${this.downloadFilename(download)}`).then((response) => {

                    let res_data = response.data
                    if(!res_data.success) return this.$message.error(res_data.message)

                    var byteCharacters = atob(response.data.filebase64);
                    var byteNumbers = new Array(byteCharacters.length);
                    for (var i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    var byteArray = new Uint8Array(byteNumbers);
                    if(download.indexOf("PDF") >= 0 || download.indexOf("pdf") >= 0)
                      var file = new Blob([byteArray], { type: 'application/pdf;base64' });
                    else
                      var file = new Blob([byteArray], { type: 'application/xml;base64' });
                    var fileURL = URL.createObjectURL(file);
                    window.open(fileURL, '_blank');
                })
//                window.open(download, '_blank');
            },
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            downloadFilename(filename){
              c = ""
              for(var i = filename.length - 1; i >= 0; i--){
                  if(filename.substring(i, i + 1) != "/"){
                    c = c + filename.substring(i, i + 1)
                  }
                  else
                    return c.split('').reverse().join('');
              }
              return c.split('').reverse().join('');
            }
        }
    }
</script>
