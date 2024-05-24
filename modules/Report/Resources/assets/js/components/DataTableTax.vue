<template>
    <div>
       

    </div>
</template>
<style>
.font-custom{
    font-size:15px !important
}
</style>
<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default {
        props: {
            resource: String,
        },
        data () {
            return {
                loading_submit:false,
                columns: [],
                records: [],
                headers: headers_token,
                document_types: [],
                pagination: {},
                search: {},
                totals: {},
                establishment: null,
                users: [],
                form: {},
                pickerOptionsDates: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.date_start > time
                    }
                },
                resource: 'reports/taxes'
            }
        },
        computed:{

            columnsTitles() {
                let titles = [];

                this.taxTitles.forEach(tax => titles.push({
                    text: `${tax.name} (${tax.rate})`,
                    value: null
                }));

                return titles;
            }
        },
        created() {
            this.initForm()
            this.$eventHub.$on('reloadData', () => {
                this.getRecords()
            })
        },
        async mounted () {

            /*await this.$http.get(`/${this.resource}/filter`)
                .then(response => {
                    this.document_types = response.data.document_types;
                    this.users = response.data.users;
                });*/


            //await this.getRecords()

        },
        methods: {
            changeDisabledDates() {
                if (this.form.date_end < this.form.date_start) {
                    this.form.date_end = this.form.date_start
                }
                // this.loadAll();
            },
            clickDownload(type) {
                let query = queryString.stringify({
                    ...this.form
                });
                window.open(`/${this.resource}/${type}/?${query}`, '_blank');
            },
            initForm(){

                this.form = {
                    date_start:null,
                    date_end:null,
                }
            },
            async getRecordsByFilter(){

                if(!this.form.date_start || !this.form.date_end)
                {
                    return this.$message.warning("Debe seleccionar fechas")
                }


                this.loading_submit = await true
                await this.getRecords()
                this.loading_submit = await false

            },
            getRecords() {
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                   // this.pagination = response.data.meta
                    //this.pagination.per_page = parseInt(response.data.meta.per_page)
                    this.loading_submit = false
                });


            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.form
                })
            },

        }
    }
</script>
