<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>

        <div class="row mb-4" v-if="form.response_api_message">
            <div class="col-md-12">
                <el-alert
                    :title="form.response_api_message"
                    type="success"
                    show-icon>
                </el-alert>
            </div>
        </div>

        <div class="row mb-4" v-if="form.response_dian_message">
            <div class="col-md-12">
                <el-alert
                    :title="form.response_dian_message"
                    type="success"
                    show-icon>
                </el-alert>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-12 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload(form.filename_pdf)">
                    <i class="fa fa-file-pdf"></i>
                </button>
                <p>Descargar PDF</p>
            </div>
             <div class="col-lg-6 col-md-6 col-sm-12 text-center font-weight-bold mt-3">

                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload(form.filename_xml)">
                    <i class="fa fa-file-excel"></i>
                </button>
                 <p>Descargar XML</p>
            </div>

        </div>
        <!-- <div class="row mt-3">
            <div class="col-md-12">
                <el-input v-model="form.customer_email">
                    <el-button slot="append" icon="el-icon-message" @click="clickSendEmail" :loading="loading">Enviar</el-button>
                </el-input>
                <small class="form-control-feedback" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small>
            </div>
        </div> -->
        <span slot="footer" class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button class="list" @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary" @click="clickNewDocument" v-if="!getIsAdjustNote">Nuevo documento</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId', 'showClose', 'isAdjustNote'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'support-documents',
                errors: {},
                form: {},
            }
        },
        created() {
            this.initForm()
        },
        computed:
        {
            getIsAdjustNote()
            {
                if(this.isAdjustNote != undefined) return this.isAdjustNote

                return false
            }
        },
        methods: {
            clickDownload(filename) {

                this.$http.get(`/${this.resource}/downloadFile/${filename}`).then((response) => {
                    
                    let res_data = response.data
                    if(!res_data.success) return this.$message.error(res_data.message)

                    var byteCharacters = atob(response.data.filebase64);
                    var byteNumbers = new Array(byteCharacters.length);
                    for (var i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    var byteArray = new Uint8Array(byteNumbers);
                    if(filename.indexOf("PDF") >= 0 || filename.indexOf("pdf") >= 0)
                      var file = new Blob([byteArray], { type: 'application/pdf;base64' });
                    else
                      var file = new Blob([byteArray], { type: 'application/xml;base64' });
                    var fileURL = URL.createObjectURL(file);
                    window.open(fileURL, '_blank');
                })

            },
            initForm() {
                this.errors = {};
                this.form = {
                    id: null,
                    number_full:null,
                    correlative_api:null,
                    message_text: null,
                    response_api_message: null,
                    filename_xml: null,
                    filename_pdf: null,
                };
            },
            async create() {

                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data
                    this.titleDialog = 'Documento: '+this.form.number_full
                })

            },
            clickPrint(format){
                window.open(`/print/document/${this.form.external_id}/${format}`, '_blank')
            },
            // clickSendEmail() {

            //     this.loading = true
            //     this.$http.post(`/${this.resource}/send-email`, {
            //             email: this.form.worker_email,
            //             prefix: this.form.prefix,
            //             consecutive: this.form.consecutive
            //         })
            //         .then(response => {
            //             if (response.data.success) {
            //                 this.$message.success(response.data.message)
            //             } else {
            //                 this.$message.error(response.data.message)
            //             }
            //         })
            //         .catch(error => {
            //             if (error.response.status === 422) {
            //                 this.errors = error.response.data.errors
            //             } else {
            //                 this.$message.error(error.response.data.message)
            //             }
            //         })
            //         .then(() => {
            //             this.loading = false
            //         })
                    
            // },
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
