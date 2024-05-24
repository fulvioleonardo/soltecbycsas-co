<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Libro de Ventas</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <div class="row mt-2">
                
                    <div class="col-md-3 pb-1">
                        <div class="form-group">
                            <label class="control-label">Fecha Desde</label>
                            <el-date-picker v-model="form.start_date" type="date"
                                            @change="changeDisabledDates"
                                            value-format="yyyy-MM-dd" format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                        </div>
                    </div>
                    <div class="col-md-3 pb-1">
                        <div class="form-group">
                            <label class="control-label">Fecha Hasta</label>
                            <el-date-picker v-model="form.end_date" type="date"
                                            :picker-options="pickerOptionsDates"
                                            value-format="yyyy-MM-dd" format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                        </div>
                    </div>

                    <div class="col-md-6 pb-1">
                        <search-customers
                            @changeCustomer="changeCustomer"
                            ref="search_customers"
                            :isClearable="true"
                        >
                        </search-customers>
                    </div>


                    <div class="col-md-3 pb-1">
                        <document-pos-types
                            @changeDocumentType="changeDocumentType"
                            ref="document_pos_types"
                            :isClearable="false"
                        >
                        </document-pos-types>
                    </div>

                    <div class="col-md-3 pb-1 center-el-checkbox">
                        <div class="form-group">
                            <el-checkbox v-model="form.summary_sales_book" class="mt-1">Libro de ventas resumido</el-checkbox>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-md-12 col-sm-12 mt-3">
                        <el-button class="submit" type="danger"  icon="el-icon-tickets" @click.prevent="clickDownload('pdf')" >Exportar PDF</el-button>
                    </div>
                </div> 
            </div> 
        </div>
 
    </div>
</template>

<script>
 
    import moment from 'moment'
    import queryString from 'query-string'
    import SearchCustomers from '@components/filters/SearchCustomers.vue'
    import DocumentPosTypes from '@components/filters/DocumentPosTypes.vue'

    export default { 
        components: {
            SearchCustomers,
            DocumentPosTypes,
        },
        data() {
            return {
                resource: 'reports/co-sales-book',                 
                form: {}, 
                pickerOptionsDates: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.start_date > time
                    }
                },
            }
        },
        async created()
        { 
            this.initForm()
        },
        mounted()
        {
            this.$refs.document_pos_types.setValue(this.form.document_type_id)
        },
        methods: 
        { 
            changeDocumentType(document_type_id)
            {
                this.form.document_type_id = document_type_id
            },
            changeCustomer(customer_id)
            {
                this.form.customer_id = customer_id
            },
            getQueryParameters() 
            {
                return queryString.stringify({
                    ...this.form
                })
            },
            changeDisabledDates() 
            {
                if (this.form.end_date < this.form.start_date) 
                {
                    this.form.end_date = this.form.start_date
                }
            },
            clickDownload(type) 
            {
                window.open(`/${this.resource}/export/${type}?${this.getQueryParameters()}`, '_blank')
            },
            initForm()
            {
                this.form = {
                    document_type_id: 'all',
                    customer_id: null,
                    start_date: moment().startOf('month').format('YYYY-MM-DD'),
                    end_date: moment().endOf('month').format('YYYY-MM-DD'),
                    summary_sales_book: false,
                }
            }, 
            
        }
    }
</script>