<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuración de documentos equivalentes</h3>
        </div>
        <div class="card-body">
            <div class="software">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.eqdocs}">
                                    <label class="control-label">Id Software Documentos Equivalentes*</label>
                                    <el-input
                                        v-model="form.ideqdocs"
                                        autofocus>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.eqdocs" v-text="errors.eqdocs[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.pineqdocs}">
                                    <label class="control-label">Pin Software Documentos Equivalentes*</label>
                                    <el-input
                                        v-model="form.pineqdocs"
                                        maxlength="5"
                                        show-word-limit>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.pineqdocs" v-text="errors.pineqdocs[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.test_set_id_eqdocs}">
                                    <label class="control-label">Test Set ID Documentos Equivalentes*</label>
                                    <el-input
                                        v-model="form.test_set_id_eqdocs">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.test_set_id_eqdocs" v-text="errors.test_set_id_eqdocs[0]"></small>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button
                                type="primary"
                                :loading="loadingSoftware"
                                @click="validateSoftware()"
                                >Guardar
                            </el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    // import Helper from "../../../mixins/Helper";

    export default {
        // mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },
        data: () => ({
            errors: {},
            form: {},
            company: {},
            loadingSoftware: false,
        }),
        async created() {
            await this.initForm()
            await this.getCompany()
            await this.setDataForm()
        },
        methods: {
            async getCompany(){
                await this.$http.post(`/company`).then(response => {
                    this.company = response.data
                })
            },
            setDataForm() {
                this.form.ideqdocs = this.company.id_software_eqdocs
                this.form.pineqdocs = this.company.pin_software_eqdocs
                this.form.test_set_id_eqdocs = this.company.test_set_id_eqdocs
            },
            initForm() {
                this.form = {
                    ideqdocs: null,
                    pineqdocs: null,
                    test_set_id_eqdocs: null
                }
            },
            validateSoftware() {
                this.loadingSoftware = true
                console.log(this.form)
                this.$http.post(`/client/configuration/store-service-software-eqdocs`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            // this.initForm()
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
                        this.loadingSoftware = false
                    })
            },
        }
    };
</script>
