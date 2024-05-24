<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de Notas de venta</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource" :applyCustomer="true">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="text-center">Fecha Emisión</th>
                            <th class="">Usuario/Vendedor</th>
                            <th>Cliente</th>
                            <th>Nota de Venta</th>
                            <th>Estado</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-center">Comprobantes</th>
                            <!-- <th>Cotización</th>
                            <th>Caso</th> -->
                            <th class="text-center">Total</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>  
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.user_name}}</td>
                            <td>{{row.customer_name}}</td>
                            <td>{{row.number_full}}</td>
                            <td>{{row.state_type_description}}</td>
                            <td class="text-center">{{row.currency_type_id}}</td>
                            <td class="text-center">
                                <template v-for="(doc,i) in row.documents">                                
                                    <label class="d-block"  :key="i">{{doc.number_full}}</label>
                                </template>
                            </td>
                            <!-- <td>{{row.quotation_number_full}}</td>
                            <td>{{row.sale_opportunity_number_full}}</td> -->
                            <td class="text-center">{{ (row.state_type_id == '11') ? "0.00" : row.total}}</td>
                            
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
                resource: 'reports/sale-notes',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>