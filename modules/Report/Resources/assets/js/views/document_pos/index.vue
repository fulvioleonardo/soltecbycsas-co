<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Documentos POS</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :applyCustomer="true" :resource="resource">
                    <tr slot="heading">
                        <th class="">#</th>
                        <th class="">Usuario/Vendedor</th>
                        <th class="">Tipo Documento</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Fecha emisión</th>
                        <th class="">Cliente</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{row.user_name}}</td>
                        <td>{{row.document_type_description}}</td>
                        <td class="text-center">{{row.number_full}}</td>
                        <td class="text-center">{{row.date_of_issue}}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td class="text-center">{{row.state_type_description}}</td>
                        <td class="text-center">{{ row.currency_type_id}}</td>
                        <td class="text-right">{{ row.total }}</td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>

<script>

    import DataTable from '../../components/DataTableReports.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'reports/document-pos',
                form: {},

            }
        },
        async created() {
        },
        methods: {


        }
    }
</script>
