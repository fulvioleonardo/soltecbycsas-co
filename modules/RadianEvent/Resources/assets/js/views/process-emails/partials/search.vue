<template>
    <el-dialog  :title="titleDialog" :visible="showDialog" @close="close" @open="create" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    
                    <!-- <div class="col-md-12">
                        <h4>Filtrar por intervalo de fechas para las búsqueda de correos electrónicos</h4>
                    </div> -->
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.search_start_date}">
                            <label class="control-label">Fecha inicio</label>
                            <el-date-picker v-model="form.search_start_date" type="date" @change="changeDisabledDates" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.search_start_date" v-text="errors.search_start_date[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.search_end_date}">
                            <label class="control-label">Fecha término</label>
                            <el-date-picker v-model="form.search_end_date" type="date" value-format="yyyy-MM-dd" :clearable="false" :picker-options="pickerOptions"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.search_end_date" v-text="errors.search_end_date[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" icon="el-icon-check">Procesar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    import queryString from 'query-string'

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'co-email-reading',
                form: {},
                errors: {},
                pickerOptions: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.search_start_date > time
                    }
                },
            }
        },
        created() { 
            this.initForm()
        },
        methods: { 
            changeDisabledDates() 
            {
                if (this.form.search_date_end < this.form.search_date_start) 
                {
                    this.form.search_date_end = this.form.date_start
                }
            },
            initForm()
            {
                this.form = {
                    search_start_date: moment().format('YYYY-MM-DD'),
                    search_end_date: moment().format('YYYY-MM-DD'),
                }
            },
            async submit()
            {
                this.loading_submit = true
                
                await this.$http.get(`/co-radian-events/search-imap-emails?${this.getQueryParameters()}`)
                    .then(response => {

                        this.$eventHub.$emit('reloadData')

                        if(response.data.success)
                        {
                            this.$message.success(response.data.message)
                            this.close()
                        }
                        else
                        {
                            this.$message.error(response.data.message)
                        }

                    })
                    .catch(error => {
                        console.log(error)
                        this.$message.error(error.response.data.message)
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            getQueryParameters() {
                return queryString.stringify({
                    ...this.form
                })
            },
            create() 
            {
                this.titleDialog = `Procesar correos por intervalo de fechas`
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
