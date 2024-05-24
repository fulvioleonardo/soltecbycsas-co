<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Software</h3>
        </div>
        <div class="card-body">
            <div class="software">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.id_software}">
                                    <label class="control-label">Id Software *</label>
                                    <el-input
                                        v-model="software.id_software"
                                        placeholder="Introduzca el Id Software asignado por la DIAN."
                                        style="text-transform: uppercase;"
                                        :disabled="false"
                                        autofocus>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.id_software" v-text="errors.id_software[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.pin_software}">
                                    <label class="control-label">Pin Software *</label>
                                    <el-input
                                        v-model="software.pin_software"
                                        placeholder="Digite el pin del Software"
                                        :disabled="false"
                                        maxlength="5"
                                        show-word-limit>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.pin_software" v-text="errors.pin_software[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.test_id}">
                                    <label class="control-label">Test Set ID *</label>
                                    <el-input
                                        v-model="software.test_id"
                                        placeholder="Introduzca el codigo del Set de Pruebas para habilitacion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.test_id" v-text="errors.test_id[0]"></small>
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
            errors: {
            },
            software: {
                id_software: '',
                pin_software: '',
                test_id: ''
            },
            loadingSoftware: false,
            company: {}
        }),

        async mounted() {
            this.errors = {}
            await this.initForm()
            await this.getCompany()
            await this.setDataSoftware()
        },
        methods: {
            async getCompany(){
                await this.$http.post(`/company`).then(response => {
                    this.company = response.data;
                })
            },
            setDataSoftware() {
                this.software.id_software = this.company.id_software
                this.software.pin_software = this.company.pin_software
                this.software.test_id = this.company.test_id
            },
            initForm() {
                this.software.id_software = ''
                this.software.pin_software = ''
                this.software.test_id = ''
            },
            validateSoftware() {
                this.loadingSoftware = true
                this.$http.post(`/client/configuration/storeServiceCompanieSoftware`, this.software)
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
