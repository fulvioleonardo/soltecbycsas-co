<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Cotizaciones</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
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
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th class="text-center" v-if="columns.delivery_date.visible">Fecha Entrega</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Cotización</th>
                        <th>Comprobantes</th>
                        <th class="text-center">Remisiones</th>
                        <!-- <th>Notas de venta</th> -->
                        <!-- <th>Estado</th> -->
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">PDF</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{ anulate_color : row.state_type_id == '11' }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td class="text-center" v-if="columns.delivery_date.visible">{{ row.delivery_date }}</td>
                        <td>{{ row.user_name }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>
                            <template v-if="row.state_type_id == '11'">
                                {{row.state_type_description}}
                            </template>
                            <template v-else>
                                <el-select v-model="row.state_type_id" @change="changeStateType(row)" style="width:120px !important">
                                    <el-option v-for="option in state_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                            </template>
                        </td>
                        <td>{{ row.identifier }}
                        </td>
                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i" v-text="document.number_full" class="d-block"></label>
                            </template>
                        </td>
                        <td class="text-center">{{ row.remission_number_full }}</td>
                        <!-- <td>
                            <template v-for="(sale_note,i) in row.sale_notes">
                                <label :key="i" v-text="sale_note.identifier" class="d-block"></label>
                            </template>
                        </td> -->
                        <!-- <td>{{ row.state_type_description }}</td> -->
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptionsPdf(row.id)">PDF</button>
                        </td>

                        <td class="text-right">
                            <el-tooltip class="item" effect="dark" content="Generar comprobante" placement="top-start">
                                <button v-if="row.state_type_id != '11' && row.btn_generate && typeUser == 'admin' && soapCompany != '03'"
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickOptions(row.id)" >
                                    Comprobante
                                </button>
                            </el-tooltip>

                            <el-tooltip class="item" effect="dark" content="Generar remisión" placement="top-start">
                                <button v-if="row.state_type_id != '11' && row.btn_generate_remission && typeUser == 'admin'"
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-primary"
                                        @click.prevent="clickGenerateRemission(row.id)" >
                                    Remisión
                                </button>
                            </el-tooltip>

                            <a v-if="row.documents.length == 0 && row.state_type_id != '11'" :href="`/${resource}/edit/${row.id}`" type="button" class="btn waves-effect waves-light btn-xs btn-info">Editar</a>
                            <button v-if="row.documents.length == 0 && row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickAnulate(row.id)">Anular</button>
                            <button @click="duplicate(row.id)"  type="button" class="btn waves-effect waves-light btn-xs btn-info">Duplicar</button>



                        </td>

                    </tr>
                </data-table>
            </div>


            <quotation-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showGenerate="true"
                              :showClose="true"></quotation-options>

            <quotation-options-pdf :showDialog.sync="showDialogOptionsPdf"
                              :recordId="recordId"
                              :showClose="true"></quotation-options-pdf>

            <quotation-generate-remission :showDialog.sync="showDialogGenerateRemission"
                              :recordId="recordId"></quotation-generate-remission>
        </div>
    </div>
</template>
<style scoped>
    .anulate_color{
        color:red;
    }
</style>
<script>

    import QuotationOptions from './partials/options.vue'
    import QuotationOptionsPdf from './partials/options_pdf.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'
    import QuotationGenerateRemission from './partials/generate_remission.vue'

    export default {
        props:['typeUser', 'soapCompany'],
        mixins: [deletable],
        components: {DataTable,QuotationOptions, QuotationOptionsPdf, QuotationGenerateRemission},
        data() {
            return {
                resource: 'quotations',
                recordId: null,
                showDialogOptions: false,
                showDialogOptionsPdf: false,
                showDialogGenerateRemission: false,
                state_types: [],
                columns: {
                    delivery_date: {
                        title: 'F.Entrega',
                        visible: false
                    }
                }
            }
        },
        async created() {
            await this.filter()
        },
        methods: {
            clickGenerateRemission(recordId){
                this.recordId = recordId
                this.showDialogGenerateRemission = true
            },
            async changeStateType(row){

                await this.updateStateType(`/${this.resource}/state-type/${row.state_type_id}/${row.id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )

            },
            filter(){
                this.$http.get(`/${this.resource}/filter`)
                            .then(response => {
                                this.state_types = response.data.state_types
                            })
            },
            clickEdit(id)
            {
                this.recordId = id
                this.showDialogFormEdit = true
            },
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickOptionsPdf(recordId = null) {
                this.recordId = recordId
                this.showDialogOptionsPdf = true
            },
            clickAnulate(id)
            {
                this.anular(`/${this.resource}/anular/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            duplicate(id)
            {
                this.$http.post(`${this.resource}/duplicate`, {id})
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('Se guardaron los cambios correctamente.')
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
                this.$eventHub.$emit('reloadData')
            }
        }
    }
</script>
