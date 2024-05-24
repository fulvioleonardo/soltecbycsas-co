<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" class="dialog-import">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="dialog-content">
                        <a href="/formats/co-documents-batch.xlsx" target="_new" class="prevent-word-break custom-link">Haga click aqui para descargar el formato que debe utilizar para realizar la carga masiva de facturas...</a>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-group text-center" :class="{'has-danger': errors.file}">
                            <el-upload
                                    ref="upload"
                                    :headers="headers"
                                    :data="{'type': type}"
                                    action="/co-documents/import"
                                    :show-file-list="true"
                                    :auto-upload="false"
                                    :multiple="false"
                                    :on-error="errorUpload"
                                    :limit="1"
                                    :on-success="successUpload">
                                <el-button slot="trigger" type="primary">Seleccione un archivo (xls, xlsx) con la informacion a cargar...</el-button>
                            </el-upload>
                        </div>
                        <small class="prevent-word-break">Asegurese de diligenciar adecuadamente todas las columnas del formato excel que descargo, cabe aclarar que cualquier error en cualquiera de estos campos, generara inconsistencias que no permitiran enviar correctamente el documento a la DIAN...</small>
                        <small class="form-control-feedback" v-if="errors.file" v-text="errors.file[0]"></small>
                    </div>

                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<style>
    .dialog-content {
        overflow: hidden;
    }

    .custom-link {
        color: black;
        text-decoration: underline;
        margin: 0 20px;
    }

    .prevent-word-break {
        word-break: keep-all;
        display: inline-block;
    }
</style>

<script>
    export default {
        props: ['showDialog', 'type'],
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                titleDialog: null,
                resource: 'co-documents',
                errors: {},
                form: {},
            }
        },

        created() {
            this.initForm()
        },

        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    file: null,
                }
            },

            create() {
                this.titleDialog = 'Carga y envio masivo de facturas'
            },

            async submit() {
                this.loading_submit = true
                await this.$refs.upload.submit()
                this.loading_submit = false
            },

            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },

            successUpload(response, file, fileList) {
                if (response.success) {
                    this.$message.success(response.message)
                    this.$eventHub.$emit('reloadData')
                    this.$refs.upload.clearFiles()
                    this.close()
                } else {
                    this.$message({message:response.message, type: 'error'})
                }
            },

            errorUpload(response) {
                console.log(response)
            }
        }
    }
</script>
