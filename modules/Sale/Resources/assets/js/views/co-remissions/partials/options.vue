<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>

        <div class="row mb-4">
            <div class="col-md-12">
                <el-alert
                    :title="`Remisión generada con éxito: ${form.number_full}`"
                    type="success"
                    show-icon>
                </el-alert>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a4')">
                    <i class="fa fa-print"></i>
                </button>
                <p>Imprimir</p>
            </div>
        </div>

        <span slot="footer" class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button class="list" @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary" @click="clickNewDocument">Nueva remisión</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId', 'showClose', 'showDownload'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'co-remissions',
                errors: {},
                form: {},
                company: {},
                locked_emission:{}
            }
        },
        async created() {
            this.initForm()
            await this.$http.get(`/companies/record`)
                .then(response => {
                    if (response.data !== '') {
                        this.company = response.data.data
                    }
                })
        },
        methods: {
            clickToPrint(format){
                window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
            },
            initForm() {
                this.errors = {};
                this.form = {
                    id: null,
                    number_full:null,
                    customer_email:null,
                    customer_phone:null,
                    correlative_api:null,
                    message_text: null,
                    response_api_message: null,
                    download_pdf: null,
                    download_xml: null,
                };
            },
            async create() {
                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data;
                    this.titleDialog = 'Remisión: '+this.form.number_full;
                });

            },
            clickPrint(format){
                window.open(`/print/document/${this.form.external_id}/${format}`, '_blank');
            },
            clickSendEmail() {
                this.loading = true
                this.$http.post(`/${this.resource}/sendEmail`, {
                    email: this.form.customer_email,
                    number: this.form.correlative_api,
                    number_full: this.form.number_full
                })
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success('El correo fue enviado satisfactoriamente')
                        } else {
                            this.$message.error('Error al enviar el correo')
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading = false
                    })
            },
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.$emit("triggerBack");
                this.initForm()
            },
        }
    }
</script>
