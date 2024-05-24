<template>
    <div  v-loading="loading">
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>DSNOF</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Documentos de soporte</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Estado</th>
                        <th>Documentos relacionados</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-center">Total</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.supplier_full_name }}</td>  
                        <td>{{ row.type_document_name }}</td>  
                        <td class="text-center">{{ row.number_full }}</td>  
                        <td class="text-center">
                            <template v-if="row.state_document_id">
                                <span class="badge bg-secondary text-white" :class="{'bg-secondary': (row.state_document_id === 1), 'bg-success': (row.state_document_id === 5), 'bg-dark': (row.state_document_id === 6)}">
                                    {{ row.state_document_name }}
                                </span>
                            </template>
                        </td>  
                        <td>
                            <template v-for="(item, index) in row.support_document_relateds">
                                <span class="ml-1" :key="index">
                                    {{ item.number_full }} 
                                    <br>
                                </span>
                            </template>
                        </td>  
                        <td class="text-center">{{ row.currency_name }}</td> 
                        <td class="text-center">{{ row.total }}</td> 
                        <td class="text-right">

                            <template v-if="!row.is_adjust_note">
                                <a class="btn waves-effect waves-light btn-xs btn-warning" :href="`/support-document-adjust-notes/create/${row.id}`">Nota de ajuste</a>
                            </template>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickOptions(row.id)">Opciones</button>

                        </td>
                    </tr>
                </data-table>
            </div>

            <support-document-options 
                :showDialog.sync="showDialogOptions"     
                :recordId="recordId"
                :showClose="true">
            </support-document-options>

        </div>
    </div>
</template>
<script>

    import SupportDocumentOptions from './partials/options.vue' 
    import DataTable from '@components/DataTableResource.vue'
    import {deletable} from '@mixins/deletable'

    export default {
        mixins: [deletable],
        components: { 
            DataTable, 
            SupportDocumentOptions, 
        },
        data() {
            return {
                showDialog: false,
                resource: 'support-documents',
                recordId: null,
                recordNumberFull: null,
                showDialogOptions:false,
                loading: false,
            }
        },
        created() { 
        },
        methods: { 
            clickOptions(recordId) {
                this.recordId = recordId
                this.showDialogOptions = true
            },  
        }
    }
</script>
