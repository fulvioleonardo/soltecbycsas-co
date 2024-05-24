<template>
    <el-dialog width="50%" :title="title" :visible="dialogImport" :close-on-click-modal="false" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col">
            <div class="form-actions text-left pt-2">
                <el-upload
                    class="upload-file"
                    ref="upload"
                    action="''"
                    :auto-upload="false"
                    :limit="1">
                    <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button>
                    <el-button style="margin-left: 10px;" size="small" type="primary" :loading="loading" @click="validate">Importar</el-button>
                    <div slot="tip" class="el-upload__tip">Solo archivos de tipo EXCEL segun plantilla descargada</div>
                    <el-button @click.prevent="close()">Cerrar</el-button>
                </el-upload>
            </div>
                    </div>
                    <div class="col-md-12 text-right" >
                        <a :href="`${route}/formatImport`" class="grey lighten-2 btn btn-sm">
                            <i class="fa fa-cloud-download-alt">
                            </i> Descargar Formato
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: {
            title: {
                required: true
            },
            route: {
                required: true
            },
            dialogImport: {
                required: true
            }
        },

        data: () => ({
            loading: false,
            form: {
            }
        }),

        methods: {
            close() {
                this.$refs.upload.clearFiles();
                this.$emit('update:dialogImport', false);
            },

            validate() {
                let Data = new FormData(this.company);
                if (this.$refs.upload.uploadFiles.length > 0){
                    this.loading = true;
                    Data.append('file', this.$refs.upload.uploadFiles[0].raw, this.$refs.upload.uploadFiles[0].name);
                    Data.append('_method', 'PUT');
                    axios
                        .post(`${this.route}/import/excel`, Data)
                            .then(response => {
                                if (response.data.success) {
                                    this.$message.success(response.data.message)
                                    console.log(response.data.message);
                                    this.$emit('refresh');
                                    this.close();
                                };
                            })
                            .catch(error => {
                                this.$message.error(error.resp.data.message);
                            })
                            .then(() => {
                                this.loading = false;
                            });
                }
                else
                    this.$message.error('Debe seleccionar un archivo de tipo EXCEL para realizar la importacion.');
            }
        }
    }
</script>
