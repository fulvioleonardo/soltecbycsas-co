<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Certificado Digital</h3>
        </div>
        <div class="card-body">
            <div class="certificado">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-2">
                            <div class="col-lg-12" v-if="response_message_certificate">
                                <h4><b>{{ response_message_certificate }}</b></h4>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.certificate64}">
                                    <el-upload
                                        ref="fileCertificado"
                                        :auto-upload="false"
                                        :on-change="handleChangeFileCertificado"
                                        :limit="1"
                                        drag
                                        action="''">
                                        <i class="el-icon-upload"></i>
                                        <div class="el-upload__text">
                                            Suelta tu archivo aquí o
                                            <em>haz clic para cargar</em>
                                        </div>
                                        <div
                                            slot="tip"
                                            class="el-upload__tip"
                                        >Solo archivos p12/pfx de cualquier tamaño</div>
                                    </el-upload>
                                    <small class="form-control-feedback" v-if="errors.certificate64" v-text="errors.certificate64[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.password_certificate}">
                                    <label class="control-label">Password Certificado *</label>
                                    <el-input
                                        v-model="certificate.password_certificate"
                                        placeholder="Introduzca el password del certificado digital."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.password_certificate" v-text="errors.password_certificate[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button
                                type="primary"
                                :loading="loadingCertificate"
                                @click="validateCertificate()"
                                >Guardar
                            </el-button>
                        </div>
                    </div>
                    <textarea hidden id="base64" rows="5"></textarea>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    function handleFileSelect(file) {
        var f = file;
        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                var binaryData = e.target.result;
                // Converting Binary Data to base 64
                var base64String = window.btoa(binaryData);
                // showing file converted to base64
                document.getElementById("base64").value = base64String;
                console.log("File converted to base64 successfuly!\nCheck in Textarea hidden");
                // return base64String;
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsBinaryString(f);
    }

    import Helper from "../../../mixins/Helper";
    export default {
        mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },

        data: () => ({
            errors: {
            },
            certificate: {
                certificate64: ''
            },
            fileCertificado: "",
            loadingCertificate: false,
            response_message_certificate: null,
        }),
        async created() {
            await this.getCompany()
        },
        mounted() {
            this.errors = {
            }
            if (window.File && window.FileReader && window.FileList && window.Blob)
                console.log("ok.");
            else
                alert("The File APIs are not fully supported in this browser.");
        },

        methods: {
            async getCompany(){
                await this.$http.post(`/company`).then(response => {
                    this.company = response.data

                    if(this.company.response_certificate){
                        const parse_certificate = JSON.parse(this.company.response_certificate)
                        this.response_message_certificate = `${parse_certificate.message} - ${parse_certificate.certificado.name}`
                    }

                })
            },
            initForm() {
                this.certificate.password_certificate = '';
                this.certificate.certificate64 = '';
                document.getElementById("base64").value = '';
            },

            pickFile() {
                this.$refs.certificate.click();
            },

            onFilePicked(e) {
                let files = e.target.files;

                if (files[0] !== undefined) {
                    this.$set(this.certificate, "certificate_name", files[0].name);
                    return;
                }
                this.$set(this.certificate, "certificate_name", "");
            },

            validateCertificate() {
                this.loadingCertificate = true
                this.certificate.certificate64 = document.getElementById("base64").value;
                this.$http.post(`/client/configuration/storeServiceCompanieCertificate`, this.certificate)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loadingCertificate = false
                        this.initForm()
                    })
            },

            handleChangeFileCertificado(file) {
                handleFileSelect(file.raw);
            },

            contructSendObject() {
                return {};
            }
        }
    };
</script>
