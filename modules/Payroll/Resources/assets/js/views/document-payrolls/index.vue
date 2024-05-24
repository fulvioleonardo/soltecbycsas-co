<template>
    <div  v-loading="loading">
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Nóminas</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Nóminas</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th class="text-left">Tipo nómina</th>
                        <th class="text-center">Nómina</th>
                        <th class="text-center">Estado</th>
                        <th class="text-left">Nóminas relacionadas</th>
                        <th class="text-center">Salario</th>
                        <th class="text-center">T. Devengados</th>
                        <th class="text-center">T. Deducciones</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.worker_full_name }}</td>  
                        <td class="text-left">{{ row.type_payroll_description }}</td>  
                        <td class="text-center">{{ row.number_full }}</td>  
                        <td class="text-center">
                            <template v-if="row.state_document_id">
                                <span class="badge bg-secondary text-white" :class="{'bg-secondary': (row.state_document_id === 1), 'bg-success': (row.state_document_id === 5), 'bg-dark': (row.state_document_id === 6)}">
                                    {{ row.state_document_name }}
                                </span>
                            </template>
                        </td>  
                        <td>
                            <template v-for="(item, index) in row.affected_adjust_notes">
                                <span class="ml-1" :key="index">
                                    {{ item.number_full }} 
                                    <template v-if="item.type_payroll_adjust_note_name">
                                        ({{ item.type_payroll_adjust_note_name }})
                                    </template>
                                    <br>
                                </span>
                            </template>
                        </td>  
                        <td class="text-center">{{ row.salary }}</td>  
                        <td class="text-center">{{ row.accrued_total }}</td>  
                        <td class="text-center">{{ row.deductions_total }}</td>  
                        <td class="text-right">

                            <template v-if="row.btn_adjust_note_elimination">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-warning" @click.prevent="clickEliminationPayroll(row.id, row.number_full)">N. Eliminación</button>
                            </template>

                            <template v-if="row.btn_adjust_note_replace">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickReplacePayroll(row.id)">N. Reemplazo</button>
                            </template>


                            <template v-if="row.btn_query">
                                <el-tooltip class="item" effect="dark" content="Consultar ZIPKEY a la DIAN" placement="top-start">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickQueryZipKey(row.id)">Consultar</button>
                                </el-tooltip>
                            </template>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickOptions(row.id)">Opciones</button>

                        </td>
                    </tr>
                </data-table>
            </div>
 

            <document-payroll-options :showDialog.sync="showDialogDocumentPayrollOptions"     
                            :recordId="recordId"
                            :showDownload="true"
                            :showClose="true">
                            </document-payroll-options>

            <document-payroll-elimination :showDialog.sync="showDialogDocumentPayrollElimination"     
                            :recordId="recordId"
                            :recordNumberFull="recordNumberFull"
                            >
                            </document-payroll-elimination>
        </div>
    </div>
</template>
<script>

    // import WorkersForm from './form.vue'
    import DocumentPayrollOptions from './partials/options.vue' 
    import DocumentPayrollElimination from './partials/elimination_payroll.vue' 
    import DataTable from '@components/DataTableResource.vue'
    import {deletable} from '@mixins/deletable'

    export default {
        mixins: [deletable],
        components: { DataTable, DocumentPayrollOptions, DocumentPayrollElimination},
        data() {
            return {
                showDialog: false,
                resource: 'payroll/document-payrolls',
                recordId: null,
                recordNumberFull: null,
                showDialogDocumentPayrollOptions:false,
                showDialogDocumentPayrollElimination:false,
                loading: false,
            }
        },
        created() { 
        },
        methods: { 
            clickReplacePayroll(recordId){
                location.href = `document-payroll-adjust-notes/${recordId}`
            },
            clickEliminationPayroll(recordId, recordNumberFull){
                this.recordId = recordId
                this.recordNumberFull = recordNumberFull
                this.showDialogDocumentPayrollElimination = true
            },
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
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogDocumentPayrollOptions = true
            },  
        }
    }
</script>
