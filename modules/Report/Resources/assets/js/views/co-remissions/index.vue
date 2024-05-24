<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Reporte de Remisiones</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th>Fecha Emisión</th>
                            <th class="">Usuario/Vendedor</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Remisión</th>
                            <th>Cotización</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-center">Total</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>  
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.user_name}}</td>
                            <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                            <td>{{row.state_type_description}}</td>
                            <td>{{row.number_full}}</td>
                            <td>{{row.quotation_number_full}}</td>
 
                            <td class="text-center">{{row.currency_name}}</td>
                            <td class="text-center">{{ row.total}}</td>
                            
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
                resource: 'reports/co-remissions',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>