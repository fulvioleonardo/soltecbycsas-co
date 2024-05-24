<template>
    <div class="form-group"> 
        <label class="control-label">
            Cliente
            <el-tooltip class="item" effect="dark" :content="`Escribir al menos ${init_search_length} caracteres para buscar`" placement="top-start">
                <i class="fa fa-info-circle"></i>
            </el-tooltip>
        </label>
        <el-select v-model="customer_id" filterable remote
            class="full"
            placeholder="Escriba el código, nombre o número de identificación"
            :remote-method="searchRemoteData"
            :clearable="isClearable"
            :loading="loading_search"
            @change="changeCustomer">
            <el-option v-for="option in records" :key="option.id" :value="option.id" :label="option.description"></el-option>
        </el-select>

        <small class="text-danger" v-if="errors_customer_id" v-text="errors_customer_id[0]"></small>
    </div>
</template> 

<script>

    export default {
        props: {
            errors_customer_id: Array,
            isClearable: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                records: [],
                all_records: [],
                loading_search: false,
                init_search_length: 2,
                customer_id: null,
            }
        },
        created() 
        {
            this.initData()
        },
        methods: {
            cleanValue()
            {
                this.customer_id = null
            },
            changeCustomer()
            {
                this.$emit('changeCustomer', this.customer_id)
            },
            async initData()
            {
                await this.getData()
                            .then(response => {
                                this.all_records = response.data.customers
                                this.filterRecords()
                            })
            },
            async getData(params = '') 
            {
                return await this.$http.get(`/persons-search-customers?${params}`)
            },
            async searchRemoteData(input) 
            {  
                if (input.length >= this.init_search_length) 
                { 
                    this.loading_search = true
                    const parameters = `input=${input}`

                    await this.getData(parameters)
                                .then(response => this.setDataFromResponse(response))  
                } 
                else 
                {
                    this.filterRecords()
                }

            },
            setDataFromResponse(response)
            {
                this.records = response.data.customers
                this.loading_search = false
                if(this.records.length == 0) this.filterRecords()
            },
            filterRecords() 
            { 
                this.records = this.all_records
            },
        }
    }
</script>